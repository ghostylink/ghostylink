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
  sh "chmod 777 pull-request.sh"
  sh env.PWD + "/pull-request.sh"
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
  step([$class: 'TasksPublisher', canComputeNew: false,  canRunOnFailed: true, defaultEncoding: '', excludePattern: '', failedTotalAll: '100', failedTotalHigh: '100', failedTotalLow: '100', failedTotalNormal: '11', healthy: '50', high: 'FIXME, FIX ME', ignoreCase: true, low: '', normal: 'TODO, TO DO', pattern: 'build/quality/task-scanner', unHealthy: '100', unstableTotalAll: '100', unstableTotalHigh: '100', unstableTotalLow: '100', unstableTotalNormal: '10'])
  step([$class: 'AnalysisPublisher', canRunOnFailed: true, canComputeNew: false, checkStyleActivated: false, defaultEncoding: '', healthy: '', unHealthy: ''])
  print currentBuild.result
  print currentBuild
  print currentBuild.displayName

  print currentBuild.description

  step([$class: 'GitHubCommitStatusSetter',commitShaSource: [$class: 'ManuallyEnteredShaSource', sha: commit_id], contextSource: [$class: 'ManuallyEnteredCommitContextSource', context: 'quality/task-scanner'], statusResultSource: [$class: 'ConditionalStatusResultSource', results: [[$class: 'AnyBuildResult', message: 'Too many remaining tasks', state: currentBuild.result]]]])

  
  
}



