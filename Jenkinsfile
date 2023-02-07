pipeline {
  agent none
  stages {
    stage('Checkout') {
      steps {
        withCredentials([usernamePassword(credentialsId: 'git-creds', passwordVariable: 'GIT_PASSWORD', usernameVariable: 'GIT_USERNAME')]) {
          checkout scm
        }
      }
    }
    stage('Build Image') {
      steps {
        sh 'docker build -t myphpapp .'
      }
    }
    stage('Push Image') {
      steps {
        withCredentials([usernamePassword(credentialsId: 'dockerhub', passwordVariable: 'DOCKERHUB_PASSWORD', usernameVariable: 'DOCKERHUB_USERNAME')]) {
          sh 'docker push myphpapp'
        }
      }
    }
    stage('Configure kubectl') {
      steps {
        withCredentials([file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')]) {
          sh 'kubectl config use-context remote-cluster --kubeconfig=$KUBECONFIG'
        }
      }
    }
    stage('Deploy to Kubernetes') {
      steps {
        sh 'kubectl apply -f k8s/deployment.yml --kubeconfig=$KUBECONFIG'
      }
    }
  }
}
