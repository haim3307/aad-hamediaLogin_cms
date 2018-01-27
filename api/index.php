<?php
require_once '../class/Social_web.php';
class AadFeed extends Social_web
{
    /**
     * Object containing all incoming request params
     * @var object
     */
    private $request;


    public function __construct()
    {
        parent::__construct();
        $this->_processRequest();
    }

    /**
     * Routes incoming requests to the corresponding method
     *
     * Converts $_REQUEST to an object, then checks for the given action and
     * calls that method. All the request parameters are stored under
     * $this->request.
     */
    private function _processRequest()
    {
        // prevent unauthenticated access to API
        $this->_secureBackend();

        // get the request
        if (!empty($_REQUEST)) {
            // convert to object for consistency
            $this->request = json_decode(json_encode($_REQUEST));
        } else {
            // already object
            $this->request = json_decode(file_get_contents('php://input'));
        }
        $action = $this->request->action;
        switch ($action){
            case 'get_posts':
                if(isset($this->request->feed_page)){
                    $this->reply(self::get_posts($this->request->feed_page));
                }
                break;
            case 'delete_post':
                if(isset($this->request->post_id)){
                    $if_own_post_q = self::query(
                        'SELECT uid FROM posts WHERE id=:post_id AND uid=:uid',
                        [':post_id'=>$this->request->post_id, ':uid'=>$_SESSION['front_user_id']]
                    );
                    if($if_own_post_q && $if_own_post_q->rowCount()){
                        $this->reply(self::delete_post($this->request->post_id));
                    }
                }
                break;
            case 'follow':
                $user_id = $this->request->uid;
                $follower_id = $_SESSION['front_user_id'];
                $this->reply(self::follow($follower_id,$user_id)?'true':'false');
        }
        // get the action
        /*
        $action = $this->request->action;

        if (empty($action)) {
            $message = array('error' => 'No method given.');
            $this->reply($message, 400);
        } else {
            // call the corresponding method
            if (method_exists($this, $action)) {
                $res = $this->$action();
                if($res){
                    $this->reply($res);
                }
            } else {
                $message = array('error' => 'Method not found.');
                $this->reply($message, 400);
            }
        }*/
    }

    /**
     * Prevent unauthenticated access to the backend
     */
    private function _secureBackend()
    {
        if (!$this->_isAuthenticated()) {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
    }

    /**
     * Check if user is authenticated
     *
     * This is just a placeholder. Here you would check the session or similar
     * to see if the user is logged in and/or authorized to make API calls.
     */
    private function _isAuthenticated()
    {
        parent::set_session();
        return isset($_SESSION['front_user_id']);
    }

    /**
     * Returns JSON data with HTTP status code
     *
     * @param  array $data - data to return
     * @param  int $status - HTTP status code
     * *@return *JSON*
     **/
    private function reply($data, $status = 200)
    {
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
        header($protocol . ' ' . $status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function hello()
    {
        $this->reply('Hello from the API!');
    }

    /**
     * Determines if the logged in user has admin rights
     *
     * This is just a placeholder. Here you would check the session or database
     * to see if the user has admin rights.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        $this->reply(true);
    }
}

$MyApi = new AadFeed();