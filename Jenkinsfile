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
        stage('yarn') {
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
      environment {
        JENKINS_MESSAGES_OVH_DATABASE_HOST  = credentials('jenkins-messages-ovh-database-host')
        JENKINS_MESSAGES_OVH_DATABASE_PORT  = credentials('jenkins-messages-ovh-database-port')
        JENKINS_MESSAGES_OVH_DATABASE_DEV_NAME  = credentials('jenkins-messages-ovh-database-dev-name')
        JENKINS_MESSAGES_OVH_DATABASE_RELEASE_NAME  = credentials('jenkins-messages-ovh-database-release-name')
        JENKINS_MESSAGES_OVH_DATABASE_MASTER_NAME  = credentials('jenkins-messages-ovh-database-master-name')
        JENKINS_MESSAGES_OVH_DATABASE_USERNAME  = credentials('jenkins-messages-ovh-database-username')
        JENKINS_MESSAGES_OVH_DATABASE_PASSWORD  = credentials('jenkins-messages-ovh-database-password')
        JENKINS_MESSAGES_OVH_MAIL_HOST  = credentials('jenkins-messages-ovh-mail-host')
        JENKINS_MESSAGES_OVH_MAIL_PORT  = credentials('jenkins-messages-ovh-mail-port')
        JENKINS_MESSAGES_OVH_MAIL_USERNAME  = credentials('jenkins-messages-ovh-mail-username')
        JENKINS_MESSAGES_OVH_MAIL_PASSWORD  = credentials('jenkins-messages-ovh-mail-password')
        JENKINS_MESSAGES_OVH_MAIL_ENCRYPTION  = credentials('jenkins-messages-ovh-mail-encryption')
        JENKINS_MESSAGES_OVH_FACEBOOK_CLIENT_ID = credentials('jenkins-messages-ovh-facebook-client-id')
        JENKINS_MESSAGES_OVH_FACEBOOK_SECRET = credentials('jenkins-messages-ovh-facebook-secret')
        JENKINS_MESSAGES_OVH_GOOGLE_CLIENT_ID = credentials('jenkins-messages-ovh-google-client-id')
        JENKINS_MESSAGES_OVH_GOOGLE_SECRET = credentials('jenkins-messages-ovh-google-secret')
      }
      steps {
        script{
            if(env.BRANCH_NAME == "release"){
                stage('release'){
                    sh './envCreator.sh -n "Messages Release" -d true -u https://release.messages.killian.ovh -a $JENKINS_MESSAGES_OVH_DATABASE_HOST -b $JENKINS_MESSAGES_OVH_DATABASE_PORT -c $JENKINS_MESSAGES_OVH_DATABASE_RELEASE_NAME -e $JENKINS_MESSAGES_OVH_DATABASE_USERNAME -f $JENKINS_MESSAGES_OVH_DATABASE_PASSWORD -g $JENKINS_MESSAGES_OVH_MAIL_HOST -i $JENKINS_MESSAGES_OVH_MAIL_PORT -j $JENKINS_MESSAGES_OVH_MAIL_USERNAME -k $JENKINS_MESSAGES_OVH_MAIL_PASSWORD -l $JENKINS_MESSAGES_OVH_MAIL_ENCRYPTION -m $JENKINS_MESSAGES_OVH_FACEBOOK_CLIENT_ID -o $JENKINS_MESSAGES_OVH_FACEBOOK_SECRET -p $JENKINS_MESSAGES_OVH_GOOGLE_CLIENT_ID -q $JENKINS_MESSAGES_OVH_GOOGLE_SECRET -r 10022 -s socket.release.messages.killian.ovh'
                }
            }else if(env.BRANCH_NAME == "master"){
                stage('master'){
                    sh './envCreator.sh -n "Messages" -d true -u https://messages.killian.ovh -a $JENKINS_MESSAGES_OVH_DATABASE_HOST -b $JENKINS_MESSAGES_OVH_DATABASE_PORT -c $JENKINS_MESSAGES_OVH_DATABASE_MASTER_NAME -e $JENKINS_MESSAGES_OVH_DATABASE_USERNAME -f $JENKINS_MESSAGES_OVH_DATABASE_PASSWORD -g $JENKINS_MESSAGES_OVH_MAIL_HOST -i $JENKINS_MESSAGES_OVH_MAIL_PORT -j $JENKINS_MESSAGES_OVH_MAIL_USERNAME -k $JENKINS_MESSAGES_OVH_MAIL_PASSWORD -l $JENKINS_MESSAGES_OVH_MAIL_ENCRYPTION -m $JENKINS_MESSAGES_OVH_FACEBOOK_CLIENT_ID -o $JENKINS_MESSAGES_OVH_FACEBOOK_SECRET -p $JENKINS_MESSAGES_OVH_GOOGLE_CLIENT_ID -q $JENKINS_MESSAGES_OVH_GOOGLE_SECRET -r 10023 -s socket.messages.killian.ovh'
                }
            } else {
                stage('dev'){
                    sh './envCreator.sh -n "Messages Dev" -d true -u https://dev.messages.killian.ovh -a $JENKINS_MESSAGES_OVH_DATABASE_HOST -b $JENKINS_MESSAGES_OVH_DATABASE_PORT -c $JENKINS_MESSAGES_OVH_DATABASE_DEV_NAME -e $JENKINS_MESSAGES_OVH_DATABASE_USERNAME -f $JENKINS_MESSAGES_OVH_DATABASE_PASSWORD -g $JENKINS_MESSAGES_OVH_MAIL_HOST -i $JENKINS_MESSAGES_OVH_MAIL_PORT -j $JENKINS_MESSAGES_OVH_MAIL_USERNAME -k $JENKINS_MESSAGES_OVH_MAIL_PASSWORD -l $JENKINS_MESSAGES_OVH_MAIL_ENCRYPTION -m $JENKINS_MESSAGES_OVH_FACEBOOK_CLIENT_ID -o $JENKINS_MESSAGES_OVH_FACEBOOK_SECRET -p $JENKINS_MESSAGES_OVH_GOOGLE_CLIENT_ID -q $JENKINS_MESSAGES_OVH_GOOGLE_SECRET -r 10021 -s socket.dev.messages.killian.ovh'
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
        sh 'yarn test'
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
              stage('rm'){
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
                        sh "sudo docker run -p 10011:9000 -p 10021:6001 --name=messages${BRANCH_NAME} --restart=always -d killianh/messages:${GIT_COMMIT}"
                    }
                }
                if(env.BRANCH_NAME == "release"){
                    stage('release'){
                        sh "sudo docker run -p 10012:9000 -p 10022:6001 --name=messages${BRANCH_NAME} --restart=always -d killianh/messages:${GIT_COMMIT}"
                    }
                }
                if(env.BRANCH_NAME == "master"){
                    stage('master'){
                        sh "sudo docker run -p 10013:9000 -p 10023:6001 --name=messages${BRANCH_NAME} --restart=always -d killianh/messages:${GIT_COMMIT}"
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
                        sh "yarn dev"
                        sh "scp -r ./public root@172.17.0.1:/var/www/messages/${BRANCH_NAME}"
                    }
                }
                if(env.BRANCH_NAME == "release"){
                    stage('release'){
                        sh "yarn production"
                        sh "scp -r ./public root@172.17.0.1:/var/www/messages/${BRANCH_NAME}"
                    }
                }
                if(env.BRANCH_NAME == "master"){
                    stage('master'){
                        sh "yarn production"
                        sh "scp -r ./public root@172.17.0.1:/var/www/messages/${BRANCH_NAME}"
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