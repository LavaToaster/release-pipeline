<?php

namespace App\Services;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\CodeBuild\CodeBuildClient;

class RunCodeBuildProject
{
    /**
     * @var CodeBuildClient
     */
    private $codeBuildClient;
    /**
     * @var CloudWatchLogsClient
     */
    private $cloudWatchLogsClient;

    public function __construct(CodeBuildClient $codeBuildClient, CloudWatchLogsClient $cloudWatchLogsClient)
    {
        $this->codeBuildClient = $codeBuildClient;
        $this->cloudWatchLogsClient = $cloudWatchLogsClient;
    }

    public function __invoke($name)
    {
        $response = $this->codeBuildClient->startBuild([
            'projectName' => $name
        ]);

        dump($response);

        $buildId = $response->get('build')['id'];

        $token = null;

        do {
            sleep(5);

            $response = $this->codeBuildClient->batchGetBuilds(['ids' => [$buildId]]);
            $build = $response->get('builds')[0];

            $data = [
                'logGroupName' => $build['logs']['groupName'],
                'logStreamName' => $build['logs']['streamName'],
            ];

            if ($token) {
                $data['nextForwardToken'] = $token;
            }

            $response = $this->cloudWatchLogsClient->getLogEvents($data);

            dump($response);

            $token = $response->get('nextForwardToken');

        } while (! $build['buildComplete']);

        echo "\n\nBuild Finished\n\n";
    }
}
