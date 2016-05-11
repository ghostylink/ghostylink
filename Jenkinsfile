node {
  stage 'Checkout'
  git url: 'https://github.com/ghostylink/ghostylink.git'
  stage 'Tests'
  step([$class: 'ArtifactArchiver', artifacts: '**/target/*.jar', fingerprint: true])
  step([$class: 'JUnitResultArchiver', testResults: '**/target/surefire-reports/TEST-*.xml'])
}
