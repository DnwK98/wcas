pipeline {
    agent any
    options {
        skipStagesAfterUnstable()
    }
    stages {
        stage('Test') {
            steps {
                echo 'Build...'
                sh 'docker/build.sh'
                echo 'Test...'
                sh 'docker/test.sh'
            }
        }
    }
}