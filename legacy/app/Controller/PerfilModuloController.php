<?php
App::uses('AppController', 'Controller');
/**
 * UsuarioModulo Controller
 *
 * @property UsuarioModulo $UsuarioModulo
 * @property PaginatorComponent $Paginator
 */
class UsuarioModuloController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->UsuarioModulo->recursive = 0;
		$this->set('usuarioModulo', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->UsuarioModulo->exists($id)) {
			throw new NotFoundException(__('Invalid usuario modulo'));
		}
		$options = array('conditions' => array('UsuarioModulo.' . $this->UsuarioModulo->primaryKey => $id));
		$this->set('usuarioModulo', $this->UsuarioModulo->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->UsuarioModulo->create();
			if ($this->UsuarioModulo->save($this->request->data)) {
				$this->Session->setFlash(__('The usuario modulo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The usuario modulo could not be saved. Please, try again.'));
			}
		}
		$usuario = $this->UsuarioModulo->Usuario->find('list');
		$modulo = $this->UsuarioModulo->Modulo->find('list');
		$status = $this->UsuarioModulo->Status->find('list');
		$this->set(compact('usuario', 'modulo', 'status'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->UsuarioModulo->exists($id)) {
			throw new NotFoundException(__('Invalid usuario modulo'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->UsuarioModulo->save($this->request->data)) {
				$this->Session->setFlash(__('The usuario modulo has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The usuario modulo could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('UsuarioModulo.' . $this->UsuarioModulo->primaryKey => $id));
			$this->request->data = $this->UsuarioModulo->find('first', $options);
		}
		$usuario = $this->UsuarioModulo->Usuario->find('list');
		$modulo = $this->UsuarioModulo->Modulo->find('list');
		$status = $this->UsuarioModulo->Status->find('list');
		$this->set(compact('usuario', 'modulo', 'status'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->UsuarioModulo->id = $id;
		if (!$this->UsuarioModulo->exists()) {
			throw new NotFoundException(__('Invalid usuario modulo'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->UsuarioModulo->delete()) {
			$this->Session->setFlash(__('The usuario modulo has been deleted.'));
		} else {
			$this->Session->setFlash(__('The usuario modulo could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
