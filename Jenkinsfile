pipeline {
  agent any
  stages {
    stage('Pre-conditions check') {
      parallel {
        stage('Php') {
          steps {
            sh 'php -v'
          }
        }
        stage('Phpcs') {
          steps {
            sh 'phpcs --version'
          }
        }
        stage('Composer') {
          steps {
            sh 'composer install'
          }
        }
        stage('Composer') {
          steps {
            sh 'yarn install'
          }
        }
        stage('Necessary folders') {
          steps {
            sh 'mkdir -p tests'
          }
        }
      }
    }
    stage('PSR2 Test') {
      steps {
        sh 'phpcs'
      }
    }
    stage('Set env') {
      steps {
        script{
            if(env.BRANCH_NAME == "release"){
                stage('release'){
                    sh 'mv .env.release .env'
                }
            }else if(env.BRANCH_NAME == "master"){
                stage('master'){
                    sh 'mv .env.master .env'
                }
            } else {
                stage('dev'){
                    sh 'mv .env.dev .env'
                }
            }
        }
      }
    }
    stage('Generate key') {
      steps {
        sh 'php artisan key:generate'
      }
    }
    stage('Units Test PHP') {
      steps {
        sh './vendor/bin/phpunit --configuration ./phpunit.xml'
      }
    }
    stage('Units Test JS') {
      steps {
        sh 'npm test'
      }
    }
    stage('SonarQube Test') {
      steps {
        script {
          scannerHome = tool 'SonarQube'
          withSonarQubeEnv('SonarQube server') {
            sh "${scannerHome}/bin/sonar-scanner"
          }
        }
        
      }
    }
    stage('Quality Gate') {
      steps {
        timeout(time: 1, unit: 'HOURS') {
          script {
            def qg = waitForQualityGate() // Reuse taskId previously collected by withSonarQubeEnv
            if (qg.status != 'OK') {
              error "Pipeline aborted due to quality gate failure: ${qg.status}"
            }
          }
          
        }
        
      }
    }
    stage('Publish reports') {
      steps {
        junit(allowEmptyResults: true, testResults: 'logfile.xml')
        step([
            $class: 'CloverPublisher',
            cloverReportDir: '',
            cloverReportFileName: 'coverage.xml',
            healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80]
        ])
      }
    }
    stage('Docker build') {
      steps {
          script {
              if(env.BRANCH_NAME == "release" || env.BRANCH_NAME == "dev"){
                  stage('nonlatest'){
                    sh "sudo docker build -t killianh/myastreinte:${GIT_COMMIT} -t killianh/myastreinte:${BRANCH_NAME} ."
                  }
              }
              if(env.BRANCH_NAME == "master"){
                  stage('latest'){
                    sh "sudo docker build -t killianh/myastreinte:${GIT_COMMIT} -t killianh/myastreinte:${BRANCH_NAME}  -t killianh/myastreinte:latest ."
                  }
              }
          }
      }
    }
    stage('Docker push') {
      steps {
        script {
          if(env.BRANCH_NAME == "release" || env.BRANCH_NAME == "dev" || env.BRANCH_NAME == "master"){
            stage('push'){
              sh "sudo docker push killianh/myastreinte"
            }
          }
        }
      }
    }
    stage('Docker pull') {
      steps {
        script {
          if(env.BRANCH_NAME == "release" || env.BRANCH_NAME == "dev" || env.BRANCH_NAME == "master"){
            stage('pull'){
              sh "sudo docker pull killianh/myastreinte:${GIT_COMMIT}"
            }
          }
        }
      }
    }
    stage('Docker stop') {
      steps {
        script {
          if(env.BRANCH_NAME == "release" || env.BRANCH_NAME == "dev" || env.BRANCH_NAME == "master"){
            stage('stop'){
              sh "sudo docker stop myastreinte${BRANCH_NAME} || true"
            }
          }
        }
      }
    }
    stage('Docker rm') {
        steps {
          script {
            if(env.BRANCH_NAME == "release" || env.BRANCH_NAME == "dev" || env.BRANCH_NAME == "master"){
              stage('stop'){
                sh "sudo docker rm myastreinte${BRANCH_NAME} || true"
              }
            }
          }
        }
    }
    stage('Docker run') {
        steps {
            script{
                if(env.BRANCH_NAME == "dev"){
                    stage('dev'){
                        sh "sudo docker run -p 10001:9000 --name=myastreinte${BRANCH_NAME} --restart=always -d killianh/myastreinte:${GIT_COMMIT}"
                    }
                }
                if(env.BRANCH_NAME == "release"){
                    stage('release'){
                        sh "sudo docker run -p 10002:9000 --name=myastreinte${BRANCH_NAME} --restart=always -d killianh/myastreinte:${GIT_COMMIT}"
                    }
                }
                if(env.BRANCH_NAME == "master"){
                    stage('master'){
                        sh "sudo docker run -p 10003:9000 --name=myastreinte${BRANCH_NAME} --restart=always -d killianh/myastreinte:${GIT_COMMIT}"
                    }
                }
            }
        }
    }
    stage('Update public folder') {
        steps {
            script{
                if(env.BRANCH_NAME == "dev"){
                    stage('dev'){
                        sh "scp -r ./public root@172.17.0.1:/var/www/myastreinte/${BRANCH_NAME}"
                    }
                }
                if(env.BRANCH_NAME == "release"){
                    stage('release'){
                        sh "scp -r ./public root@172.17.0.1:/var/www/myastreinte/${BRANCH_NAME}"
                    }
                }
                if(env.BRANCH_NAME == "master"){
                    stage('master'){
                        sh "scp -r ./public root@172.17.0.1:/var/www/myastreinte/${BRANCH_NAME}"
                    }
                }
            }
        }
    }
    stage('Worker') {
        steps {
            script{
                if(env.BRANCH_NAME == "release" || env.BRANCH_NAME == "dev" || env.BRANCH_NAME == "master"){
                    stage('push'){
                      sh "sudo docker exec myastreinte${BRANCH_NAME} nohup php artisan queue:work --tries=3 &"
                    }
                }
            }
        }
    }
  }
  post {
    always {
      junit 'logfile.xml'
    }
    
  }
}