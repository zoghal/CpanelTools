
<?php


class Cpanel
{

    private $accunts = [];

    /**
     * Constructor for the class.
     *
     * This function is called when an object of the class is created.
     * It initializes the object by calling the fetchAccuntsInfo() method.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fetchAccuntsInfo();
    }

    /**
     * Fetches account information from '/etc/userdatadomains.json' and stores it in the $accunts property.
     *
     * @return void
     */
    private function fetchAccuntsInfo(): void
    {
        $hosts = file_get_contents('/etc/userdatadomains.json');
        $hosts = json_decode($hosts, true);
        foreach ($hosts as $key => $value) {
            if ($value[2] !== 'main') continue;
            $this->accunts[$value[0]] = [
                'domain' => $value[3],
                'path' =>   $value[4],
            ];
        }
    }

    /**
     * Retrieves the usernames of all accounts.
     *
     * @return array The usernames of all accounts.
     */
    public function getAccuntsByUsername(): array
    {
        return array_keys($this->accunts);
    }


    /**
     * Retrieves the paths of all accounts.
     *
     * @return array An array of account paths.
     */
    public function getAccuntsByPath(): array
    {
        $res = array_map(fn($value) => $value['path'], $this->accunts);
        return array_values($res);
    }
}
