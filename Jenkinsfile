node {
  stage 'Checkout'
  git url: 'https://github.com/ghostylink/ghostylink.git'
  stage 'Tests'
  step([$class: 'JUnitResultArchiver', testResults: '**/target/surefire-reports/TEST-*.xml'])
}
