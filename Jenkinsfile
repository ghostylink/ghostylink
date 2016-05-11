node {
  stage 'Checkout'
  checkout scm
  sh "wget http://jenkins.ghostylink.org/job/ghostylink_unit/ws/tests_result/junit.xml"
  sh "pwd"
  sh "touch junit.xml"
  
  stage 'Tests'
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
  step([$class: 'GitHubCommitStatusSetter', contextSource: [$class: 'ManuallyEnteredCommitContextSource', context: 'Jenkins'], statusResultSource: [$class: 'ConditionalStatusResultSource', results: [[$class: 'AnyBuildResult', message: 'ok for the win', state: 'SUCCESS']]]])
}



