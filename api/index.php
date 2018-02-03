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
                if($feed_page = filter_input(INPUT_GET,'feed_page',FILTER_VALIDATE_INT)){
                    if(!isset($this->request->posted_by))
                        $posts = self::get_posts($feed_page);
                    else
                        $posts = self::get_posts($feed_page,$this->request->posted_by);
                    $this->reply($posts);
                }
                break;
            case 'delete_post':
                if($post_id = filter_input(INPUT_POST,'post_id',FILTER_VALIDATE_INT)){
                    $if_own_post_q = self::query(
                        'SELECT uid FROM posts WHERE id=:post_id AND uid=:uid',
                        [':post_id'=>$post_id, ':uid'=>$_SESSION['front_user_id']]
                    );
                    if($if_own_post_q && $if_own_post_q->rowCount()){
                        $this->reply(self::delete_post($post_id));
                    }
                }
                break;
            case 'add_post':
                $to_id = isset($this->request->to_id)?filter_input(INPUT_POST,'to_id',FILTER_VALIDATE_INT):null;
                $new_post = self::add_new_post($this->request->content,$to_id);
                $this->reply(['post'=>$new_post,'msg'=>($new_post === null?'empty':'added')]);
                break;
            case 'update_post':
                $to_id = isset($this->request->post_id)?filter_input(INPUT_POST,'post_id',FILTER_VALIDATE_INT):null;
                $post_title= isset($this->request->post_title)?filter_input(INPUT_POST,'post_title',FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES):null;
                if($to_id && $post_title){
                    $new_post = ['title'=>self::update_post($to_id,$post_title)];
                    $this->reply(['post'=>$new_post,'msg'=>($new_post === null?'fail':'updated')]);
                }else{
                    $this->reply(['post'=>[],'msg'=>'fail']);
                }
                break;
            case 'follow':
                $user_id = $this->request->uid;
                $follower_id = $_SESSION['front_user_id'];
                $this->reply(self::follow($follower_id,$user_id)?'true':'false');
                break;
            case 'like_post':
                $this->reply(self::like_post($this->request->post_id));
                break;
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
        return parent::isLoggedIn();
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