 <?php
class AjaxController extends AdminAppController {
    var $uses = array();

    function beforeFilter() {
        parent::beforeFilter();

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = false;
    }

    function updateOrder() {
        $jsonMessage = array();

        if(!empty($this->request->query['m']) && !empty($this->request->query['orderdata'])) {
            $modelName = $this->request->query['m'];
            App::uses($modelName, 'Model');
            $myModel = new $modelName();

            foreach($this->request->query['orderdata'] as $order => $id) {
                $params = array();
                $params['id'] = $id;
                $params['order'] = $order;
                $myModel->create();
                $myModel->save($params);
            }

            $jsonMessage['type'] = 'success';
            $jsonMessage['msg'] = __('New order saved successfully');
        }else {
            $jsonMessage['type'] = 'important';
            $jsonMessage['msg'] = __('An error occur while saving the roder');
        }

        return json_encode($jsonMessage);
    }
}