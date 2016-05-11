node {
  stage 'Checkout'
  checkout scm
  sh "wget http://jenkins.ghostylink.org/job/ghostylink_unit/ws/tests_result/junit.xml"
  sh "pwd"
  sh "touch junit.xml"
  sh "git rev-list -n 1 HEAD^2"
  sh "env"
  stage 'Tests'
  sh "git rev-list -n 1 HEAD^2 > .git/commit-id"                        
  commit_id = readFile('.git/commit-id')
  sh "echo $commit_id"
  print commit_id
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
  step([$class: 'TasksPublisher', canComputeNew: false, defaultEncoding: '', excludePattern: '', failedTotalAll: '30', failedTotalHigh: '10', failedTotalLow: '40', failedTotalNormal: '20', healthy: '5', high: 'FIXME, FIX ME', ignoreCase: true, low: '', normal: 'TODO, TO DO', pattern: '**/*', unHealthy: '50', unstableTotalAll: '2', unstableTotalHigh: '5', unstableTotalLow: '20', unstableTotalNormal: '10'])
  step([$class: 'GitHubCommitStatusSetter',commitShaSource: [$class: 'ManuallyEnteredShaSource', sha: commit_id], contextSource: [$class: 'ManuallyEnteredCommitContextSource', context: 'yolo'], statusResultSource: [$class: 'ConditionalStatusResultSource', results: [[$class: 'AnyBuildResult', message: 'ok for the win', state: 'SUCCESS']]]])
  
}



