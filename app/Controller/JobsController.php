<?php
class JobsController extends AppController {
  public $name = 'Jobs';

  public function index(){
    $cat_options = array(
        'order' => array('Category.name' => 'desc')
      );

    $categories = $this->Job->Category->find('all', $cat_options);

    $this->set('categories', $categories);
    $options = array(
        'order' => array('Job.created' => 'desc'),
        'limit' => 5
      );

    // get Job info
    $jobs = $this->Job->find('all',$options);

    $this->set('title_for_layout', 'SimpleJobs | Welcome');

    $this->set('jobs', $jobs);
  }


  public function browse($category = null){
    $conditions = array();

    if($this->request->is('post')) {
      if(!empty($this->request->data('keywords'))) {
        $conditions[] = array('OR' => array(
          'Job.title LIKE' => "%".$this->request->data('keywords')."%",
          'Job.description LIKE' => "%".$this->request->data('keywords')."%"
          )
        );
      }
    }

    if(!empty($this->request->data('state')) && $this->request->data('state') != 'Select State') {
      $conditions[] = array(
        'Job.state like' => "%".$this->request->data('state')."%"
      );
    }

    if(!empty($this->request->data('category')) && $this->request->data('category') != 'Select Category') {
      $conditions[] = array(
        'Job.state like' => "%".$this->request->data('category')."%"
      );
    }

    $cat_options = array(
        'order' => array('Category.name' => 'desc')
      );

    $categories = $this->Job->Category->find('all', $cat_options);

    $this->set('categories', $categories);

    if($category != null) {
      $conditions[] = array(
        'Job.category_id LIKE' => "%".$category."%"
      );
    }

    $options = array(
        'order' => array('Job.created' => 'desc'),
        'conditions' => $conditions,
        'limit' => 8
      );
    
    $jobs = $this->Job->find('all',$options);

    $this->set('title_for_layout', 'SimpleJobs | Browse');

    $this->set('jobs', $jobs);
  }

  public function view($id) {
    if(!$id) {
      throw new NotFoundException(__('Invalid job listing'));
    }

    $job = $this->Job->findById($id);
    
    if(!$job) {
      throw new NotFoundException(__('Invalid job listing'));
    }

    $this->set('title_for_layout', $job['Job']['title']);

    $this->set('job',$job);
  }

  public function add() {
    $cat_options = array(
      'order' => array('Category.name' => 'asc')
    );
    $categories = $this->Job->Category->find('list', $cat_options);
    $this->set('categories', $categories);

    $types = $this->Job->Type->find('list');
    $this->set('types', $types);

    if($this->request->is('post')){
      $this->Job->create();

      $this->request->data['Job']['user_id'] = $this->Auth->user('id');

      if($this->Job->save($this->request->data)){
        $this->Session->setFlash(__('Your job has been listed.'));
        return $this->redirect(array('action' => 'index'));
      }

      $this->Session->setFlash(__('Unable to list your job. Please try again.'));
    }
  }

  public function edit($id) {
    $cat_options = array(
      'order' => array('Category.name' => 'asc')
    );
    $categories = $this->Job->Category->find('list', $cat_options);
    $this->set('categories', $categories);

    $types = $this->Job->Type->find('list');
    $this->set('types', $types);

    if(!$id) {
      throw new NotFoundException(__('Invalid job listing'));
    }

    $job = $this->Job->findById($id);

    if(!$job) {
      throw new NotFoundException(__('Invalid job listing'));
    }
    
    if($this->request->is(array('job','put'))){
      $this->Job->id = $id;

      if($this->Job->save($this->request->data)){
        $this->Session->setFlash(__('Your job has been updated.'));
        return $this->redirect(array('action' => 'index'));
      }

      $this->Session->setFlash(__('Unable to edit your job. Please try again.'));
    }

    if(!$this->request->data) {
      $this->request->data = $job;
    }
  }

  public function delete($id) {
    if ($this->request->is('post') && !$this->request->is('put')) {
      throw new MethodNotAllowedException();
    }

    if ($this->Job->delete($id)) {
      $this->Session->setFlash(
            __('The job with id: %s has been deleted.', h($id))
      );
      return $this->redirect(array('action' => 'index'));
    }
  }
}