<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\TaskRun;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/trigger", name="trigger")
     */
    public function triggerAction()
    {
        $now = new \DateTime('now', new \DateTimeZone('america/toronto'));
        $criteria = [
            'hour' => $now->format('G'),
            'minute' => $now->format('i'),
        ];

        $tasks = $this->getDoctrine()->getManager()->getRepository("AppBundle:Task")->findBy($criteria);
        $output = [];
        foreach ($tasks as $task) {
            $this->logOutput($task, $this->executeTask($task));
        }
        return new JsonResponse($output);
    }

    private function executeTask(Task $task)
    {
        $server = $task->getServer();
        $client = new SSH2($server->getHostname());
        $key = new RSA();
        $key->loadKey($server->getPassword());
        $loggedIn = $client->login($server->getUser(), $key);
        if (!$loggedIn) {
            die("Bad login");
        }
        return $client->exec($task->getTask());
    }

    private function logOutput(Task $task, $output)
    {
        $taskRun = new TaskRun();
        $taskRun->setRuntime(new \DateTime());
        $taskRun->setTask($task);
        $taskRun->setOutput($output);

        $em = $this->getDoctrine()->getManager();
        $em->persist($taskRun);
        $em->flush();
    }

    /**
     * @Route("/taskruns", name="runs")
     * @Template()
     */
    public function tasksAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository("AppBundle:TaskRun")->findBy([], ['runtime' => 'desc']);
        return [
            'tasks' => $tasks,
        ];
    }
}
