node {

  stage 'Checkout code'
  checkout scm
  sh "wget http://jenkins.ghostylink.org/job/ghostylink_unit/ws/tests_result/junit.xml"
  sh "touch junit.xml"
  
  stage 'Unit tests'

  commit_to_merge = commit_to_merge()
  target_commit = commit_target()

  step_junit()

  stage 'Quality code'
  step_task_scanner(commit_to_merge, target_commit)  
  step_publish_github(commit_to_merge, "quality/task-scanner", "New tasks threshold exceded", currentBuild.result)  
}

def commit_to_merge() {

  sh "git rev-list -n 1 HEAD^2 > .git/commit-id"  
  commit_id = readFile('.git/commit-id')
  commit_id_length = commit_id.length() - 2
  commit_id = commit_id[0..commit_id_length]
  return commit_id

}

def commit_target() {

  sh "git rev-list -n 1 HEAD^1 > .git/target-merge-commit-id"
  target_commit_id = readFile('.git/target-merge-commit-id')
  target_commit_id_length = target_commit_id.length() - 2
  target_commit_id = target_commit_id[0..target_commit_id_length]
  return target_commit_id
}

def step_task_scanner(commit_id, target_merge_id) {
    sh "git diff $target_merge_id $commit_id  > pull-request.diff"
    cmd = readFile('tests/Jenkins/tasks-pull-request.sh')
    sh cmd
    step([$class: 'TasksPublisher',
          canComputeNew: false,
          canRunOnFailed: true,
          defaultEncoding: '',
          excludePattern: '**/Jenkinsfile',
          failedTotalAll: '5',
          failedTotalHigh: '0',
          failedTotalLow: '10',
          failedTotalNormal: '5',
          healthy: '50',
          high: 'FIXME,FIX ME',
          normal: 'TODO,TO DO',
          low:'WHY',
          ignoreCase: true,          
          pattern: 'build/quality/tasks-scanner/',
          unHealthy: '100',
          unstableTotalAll: '5',
          unstableTotalHigh: '0', 
          unstableTotalLow: '10',
          unstableTotalNormal: '5'])
}

def step_junit() {
  step([$class: 'JUnitResultArchiver', testResults: '**/junit.xml'])
}

def step_publish_github(commit_id, context, message, result) {
  step([$class: 'GitHubCommitStatusSetter',
        commitShaSource: [$class: 'ManuallyEnteredShaSource',
                          sha: commit_id],
        contextSource: [$class: 'ManuallyEnteredCommitContextSource',
                         context: context],
        statusResultSource: [$class: 'ConditionalStatusResultSource',
                             results: [[$class: 'AnyBuildResult',
                                        message: message,
                                        state: result]]
                            ]
        ])
   return currentBuild.result == "SUCCESS"
}
