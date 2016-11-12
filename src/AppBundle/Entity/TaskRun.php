<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="task_run")
 */
class TaskRun
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task")
     * @ORM\JoinColumn()
     */
    private $task;

    /**
     * @ORM\Column(type="datetime")
     */
    private $runtime;

    /**
     * @ORM\Column(type="text")
     */
    private $output;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set runtime
     *
     * @param \DateTime $runtime
     *
     * @return TaskRun
     */
    public function setRuntime($runtime)
    {
        $this->runtime = $runtime;

        return $this;
    }

    /**
     * Get runtime
     *
     * @return \DateTime
     */
    public function getRuntime()
    {
        return $this->runtime;
    }

    /**
     * Set output
     *
     * @param string $output
     *
     * @return TaskRun
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get output
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set task
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return TaskRun
     */
    public function setTask(\AppBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \AppBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }
}
