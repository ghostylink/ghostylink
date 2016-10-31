node {    
    stage 'Checkout code'
    checkout scm
    
    stage 'Preparing environment'
    sh 'curl -s https://getcomposer.org/installer | php'
    sh 'php composer.phar update'
    sh 'bower install'
    
    // Create a suffix specific to the build
    container_suffix = URLDecoder.decode(env.BUILD_TAG).replaceAll(/\//, "-")    
    container = docker.image('ghostylink/ci-tools:latest')    
    selenium_node = docker.image('selenium/node-firefox:2.53.0')
    
    maildev_name = "maildev-${container_suffix}"
    maildev_run_args = "--name $maildev_name"
    print maildev_run_args
    maildev = docker.image('djfarrelly/maildev').withRun(maildev_run_args){
        firefox_node_opt = "-e SE_OPTS='-browser browserName=firefox,maxInstances=1,applicationName=$container_suffix'"
        selenium_node_args = "--link selenium-hub:hub --link $maildev_name $firefox_node_opt"
        print selenium_node_args
        selenium_node.withRun(selenium_node_args){
            container.pull()
            citools_args = "-u root --link selenium-hub:hub --link $maildev_name"
            container.inside(citools_args) {
                sh 'bash /image/init.sh'
                sh 'ant prepare'
                stage 'Unit tests'
                sh 'ant tests-unit'
                stage 'Functional tests'
                sh 'ant tests-functional'
                step_junit()
                step_clover()
                stage 'Quality'
                sh 'ant quality'
                if (is_pull_request(env.CHANGE_URL)) {    
                    target_merge = commit_id("HEAD^1")
                    changes_commit = commit_id("HEAD^2")
                }
                else {
                    changes_commit = commit_id("HEAD")    
                    target_merge = null
                }
                    
                step_task_scanner(changes_commit, target_merge)    
                step_checktyle()  
                step_pmd()
                step_cpd() 
            }
        }    
    }
}

def commit_id(expr) {

  sh "git rev-list -n 1 $expr > .git/commit-${expr}-id"  
  commit_id = readFile(".git/commit-${expr}-id")
  commit_id_length = commit_id.length() - 2
  commit_id = commit_id[0..commit_id_length]
  return commit_id

}

def step_task_scanner(commit_id, target_merge_id) {
    if (target_merge_id) {
        sh "git diff $target_merge_id $commit_id  > pull-request.diff"
        cmd = readFile('tests/Jenkins/tasks-pull-request.sh')
        sh cmd
        directory = 'build/quality/tasks-scanner/'
        failedTotalAll = '5'
        failedTotalHigh = '0'
        failedTotalLow = '10'
        failedTotalNormal = '5'
        unstableTotalAll = '5'
        unstableTotalHigh = '0'
        unstableTotalLow = '10'
        unstableTotalNormal = '5'
    }
    else {
        directory = '**/*.php'
        failedTotalAll = '15'
        failedTotalHigh = '5'
        failedTotalLow = '10'
        failedTotalNormal = '10'
        unstableTotalAll = '10'
        unstableTotalHigh = '3'
        unstableTotalLow = '7'
        unstableTotalNormal = '5'
    }   
    exclude = 'vendor/**'
    res = step([$class: 'TasksPublisher',
          canComputeNew: false,
          canRunOnFailed: true,
          defaultEncoding: '',
          excludePattern: exclude,
          failedTotalAll: failedTotalAll,
          failedTotalHigh: failedTotalHigh,
          failedTotalLow: failedTotalLow,
          failedTotalNormal: failedTotalNormal,
          healthy: '5',
          high: 'FIXME,FIX ME',
          normal: 'TODO,TO DO',
          low:'WHY',
          ignoreCase: true,          
          pattern: directory,
          unHealthy: '20',
          unstableTotalAll: unstableTotalAll,
          unstableTotalHigh: unstableTotalHigh, 
          unstableTotalLow: unstableTotalLow,
          unstableTotalNormal: unstableTotalNormal])
}

def step_junit() {
  step([$class: 'JUnitResultArchiver', testResults: '**/build/results/junit*.xml'])
}

def step_clover() {
  step([$class: 'CloverPublisher', cloverReportDir:'build/results', cloverReportFileName: 'clover.xml'])
}
def step_pmd() {
    step([$class: 'PmdPublisher',
          canComputeNew: false,
          canRunOnFailed: true,
          defaultEncoding: '',
          healthy: '25',
          pattern: '**/build/results/phpmd.xml',
          unHealthy: '50'])
}

def step_cpd() {
    step([$class: 'DryPublisher',
          canComputeNew: false,
          canRunOnFailed: true,
          defaultEncoding: '',
          healthy: '5',
          pattern: 'build/results/phpcpd.xml',
          unHealthy: '15'])

}
def step_checktyle() {
  step([$class: 'CheckStylePublisher', 
        canComputeNew: false,
        canRunOnFailed: true, 
        defaultEncoding: '', 
        failedTotalHigh: '15',
        failedTotalLow: '35', 
        failedTotalNormal: '25',
        healthy: '10',
        pattern: '**/build/results/checkstyle.xml', 
        unHealthy: '50', 
        unstableTotalHigh: '15',
        unstableTotalLow: '17',
        unstableTotalNormal: '12'])
}

def status_is_worst(oldStatus, newStatus) {
    def statusPriority = [:]
    statusPriority['SUCCESS'] = 2
    statusPriority['FAILURE'] = 0
    statusPriority['UNSTABLE'] = 1
    
    return statusPriority[newStatus] < statusPriority[oldStatus]
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
}

def is_pull_request(text) {
  def matcher = text =~ 'pull'
  return matcher.size() != 0 && matcher[0] == "pull"
}
