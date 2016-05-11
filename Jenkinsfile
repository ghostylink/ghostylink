step{
  node {
  stage 'Checkout'
  git url: 'https://github.com/ghostylink/ghostylink.git'
  sh "wget http://jenkins.ghostylink.org/job/ghostylink_unit/ws/tests_result/junit.xml"
  sh "pwd"
  sh "touch junit.xml"
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
  
  stage 'Tests'
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
}
}
step {
node {
  stage 'Checkout'
  git url: 'https://github.com/ghostylink/ghostylink.git'
  sh "wget http://jenkins.ghostylink.org/job/ghostylink_unit/ws/tests_result/junit.xml"
  sh "pwd"
  sh "touch junit.xml"
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
  
  stage 'Tests'
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
}}
