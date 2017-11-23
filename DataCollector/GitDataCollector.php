<?php
namespace Jbtcd\SymfonyGitInformation\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GitDataCollector
 */
class GitDataCollector extends DataCollector
{

    /**
     * @param $maxCommits
     * @param $repositoryName
     * @param $repositoryUrl
     * @param $repositoryCommitUrl
     * @param $repositoryBranchUrl
     */
	public function __construct($maxCommits, $repositoryName, $repositoryUrl,$repositoryCommitUrl, $repositoryBranchUrl)
	{
		$this->data['maxCommits'] = $maxCommits;
        $this->data['repositoryName'] = $repositoryName;
        $this->data['repositoryUrl'] = $repositoryUrl;
        $this->data['repositoryCommitUrl'] = $repositoryCommitUrl;
        $this->data['repositoryBranchUrl'] = $repositoryBranchUrl;
	}

	/**
	 * Collect Git data for DebugBar (branch,commit,author,email,merge,date,message)
	 *
	 * @param Request $request
	 * @param Response $response
	 * @param \Exception $exception
	 */
	public function collect(Request $request, Response $response, \Exception $exception = null)
	{
	    $this->data['gitData'] = false;

        exec("git branch", $data);

        if (isset($data[0])) {
            foreach ($data as $branch) {
                if (strstr($branch, "* ")) {
                    $this->data['gitData'] = true;
                    $this->data['branch'] = trim(substr($branch, strpos($branch, "* ") + 2));
                }
            }
        }

        if ($this->getData("gitData")) {
            $this->data['commits'] = $this->fetchCommits($this->getData('maxCommits'));
        }
	}

	public function getRepositoryName()
    {
        return $this->getData("repositoryName");
    }

    public function getRepositoryUrl()
    {
        return $this->getData("repositoryUrl");
    }

    public function getCommitUrl()
    {
        return $this->getData("repositoryCommitUrl");
    }

    public function getBranchUrl()
    {
        return $this->getData("repositoryBranchUrl");
    }

	public function getLastCommit()
    {
        return $this->getData('commits')[0];
    }

	/**
	 * true if there is some data : used by the view
	 *
	 * @return string
	 */
	public function getGitData()
	{
		return $this->getData('gitData');
	}

	public function getCommits()
    {
        return $this->getData('commits');
    }

	/**
	 * Actual branch name
	 *
	 * @return string
	 */
	public function getBranch()
	{

		return $this->getData('branch');

	}

	/**
	 * DataCollector name : used by service declaration into container.yml
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'git';
	}

    /**
     * Checks and returns the data
     *
     * @param string $data
     * @return string
     */
    private function getData($data)
    {
        return (isset($this->data[$data])) ? $this->data[$data] : '';

    }

    private function fetchCommits($max)
    {
        $commits = [];

        for ($i = 0; $i < $max; $i++) {
            exec("git log --skip {$i} -n 1", $data);

            foreach ($data as $d) {
                if (strpos($d, 'commit') === 0) {
                    $commit['id'] = substr($d, 7);
                } elseif (strpos($d, 'Author') === 0) {
                    preg_match('$Author: ([^<]+)<(.+)>$', $d, $author);

                    if (isset($author[1])) {
                        $commit['author'] = trim($author[1]);
                    }
                    if (isset($author[2])) {
                        $commit['email'] = $author[2];
                    }
                } elseif (strpos($d, 'Date') === 0) {
                    $date = trim(substr($d, 5)); // ddd mmm n hh:mm:ss yyyy +gmt
                    // date of commit
                    $dateCommit = date_create($date);
                    // actual date at runtime
                    $dateRuntime = new \DateTime();
                    $dateNow = date_create($dateRuntime->format('Y-m-d H:i:s'));
                    // difference
                    $time = date_diff($dateCommit, $dateNow);

                    // static time difference : minutes and seconds
                    $commit['timeCommitIntervalMinutes'] = $time->format('%y')*365*24*60+$time->format('%m')*30*24*60+$time->format('%d')*24*60+$time->format('%h')*60+$time->format('%i');
                    $commit['timeCommitIntervalSeconds'] = $time->format('%s');

                    // full readable date
                    $commit['date'] = $date;
                } elseif (strpos($d, 'Merge') === 0) {
                    // merge information
                    $commit['merge'] = trim(substr($d, 6));
                } else {
                    $commit['message'] = trim($d);
                }
            }

            if (!empty($data)) {
                $commits[] = $commit;
            } else {
                $i = $max;
            }

            unset($data, $commit);
        }

        return $commits;
    }
}
