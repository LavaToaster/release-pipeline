<?php

namespace App\Services;

use Aws\CodeBuild\CodeBuildClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateCodeBuildProject
{
    /**
     * @var CodeBuildClient
     */
    private $codeBuildClient;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        CodeBuildClient $codeBuildClient,
        ContainerInterface $container
    ) {
        $this->codeBuildClient = $codeBuildClient;
        $this->container = $container;
    }

    public function __invoke($name)
    {
        $serviceRole = $this->container->getParameter('codebuild.service_role_arn');
        $bucketName = $this->container->getParameter('codebuild.bucket_name');

        $response = $this->codeBuildClient->createProject([
            'name' => $name,
            'serviceRole' => $serviceRole,
            'artifacts' => [
                'type' => 'S3',
                'location' => $bucketName,
                'name' => $name,
                'path' => 'output',
            ],
            'source' => [
                'type' => 'S3',
                'location' => $bucketName.'/'.$name.'.zip'
            ],
            'cache' => [
                'type' => 'S3',
                'location' => $bucketName.'/cache'

            ],
            'environment' => [
                'computeType' => 'BUILD_GENERAL1_LARGE',
                'image' => 'composer:latest',
                'type' => 'LINUX_CONTAINER'
            ],
        ]);

        return $response->get('project')['name'];
    }
}
