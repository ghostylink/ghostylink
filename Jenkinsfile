node {
  stage 'Checkout'
  git url: 'https://github.com/ghostylink/ghostylink.git'
  stage 'Tests'
  sh "wget http://jenkins.ghostylink.org/job/ghostylink_unit/ws/tests_result/junit.xml"
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
}
