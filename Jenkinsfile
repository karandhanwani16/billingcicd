pipeline {
  agent none
  stages {
    stage('Checkout') {
      steps {
        checkout scmGit(branches: [[name: '*/main']], extensions: [], userRemoteConfigs: [[credentialsId: 'd37352dc-20db-4025-abbc-3434a58d7d27', url: 'https://github.com/karandhanwani16/billingcicd.git']])
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
