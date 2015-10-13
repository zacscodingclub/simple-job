<?php
class JobsController extends AppController {
  public $name = 'Jobs';

  public function index(){
    $cat_options = array(
        'order' => array('Category.name' => 'asc')
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
        'order' => array('Category.name' => 'asc')
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
}