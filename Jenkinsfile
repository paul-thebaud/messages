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
        stage('Yarn') {
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
                    sh './envCreator.sh -n "Messages Dev" -d true -u https://dev.messages.killian.ovh/ -a mysql.killian.ovh -b 32768 -c devmessages -e root -f A9BA19F564 -g ssl0.ovh.net -i 587 -j messages@killian.ovh -k A9BA19F564 -l tls'
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
                    sh "sudo docker build -t killianh/messages:${GIT_COMMIT} -t killianh/messages:${BRANCH_NAME} ."
                  }
              }
              if(env.BRANCH_NAME == "master"){
                  stage('latest'){
                    sh "sudo docker build -t killianh/messages:${GIT_COMMIT} -t killianh/messages:${BRANCH_NAME}  -t killianh/messages:latest ."
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
              sh "sudo docker push killianh/messages"
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
              sh "sudo docker pull killianh/messages:${GIT_COMMIT}"
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
              sh "sudo docker stop messages${BRANCH_NAME} || true"
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
                sh "sudo docker rm messages${BRANCH_NAME} || true"
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
                        sh "sudo docker run -p 10011:9000 --name=messages${BRANCH_NAME} --restart=always -d killianh/messages:${GIT_COMMIT}"
                    }
                }
                if(env.BRANCH_NAME == "release"){
                    stage('release'){
                        sh "sudo docker run -p 10012:9000 --name=messages${BRANCH_NAME} --restart=always -d killianh/messages:${GIT_COMMIT}"
                    }
                }
                if(env.BRANCH_NAME == "master"){
                    stage('master'){
                        sh "sudo docker run -p 10013:9000 --name=messages${BRANCH_NAME} --restart=always -d killianh/messages:${GIT_COMMIT}"
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
                        sh "scp -r ./public root@172.17.0.1:/var/www/messages/${BRANCH_NAME}"
                    }
                }
                if(env.BRANCH_NAME == "release"){
                    stage('release'){
                        sh "scp -r ./public root@172.17.0.1:/var/www/messages/${BRANCH_NAME}"
                    }
                }
                if(env.BRANCH_NAME == "master"){
                    stage('master'){
                        sh "scp -r ./public root@172.17.0.1:/var/www/messages/${BRANCH_NAME}"
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
                      sh "sudo docker exec messages${BRANCH_NAME} nohup php artisan queue:work --tries=3 &"
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