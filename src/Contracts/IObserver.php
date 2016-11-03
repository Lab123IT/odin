<?php

namespace Lab123\Odin\Contracts;

use Lab123\Odin\Entities\Entity;

interface IObserver
{
	/**
	 * @param Entity $entity
	 */
	public function creating($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function created($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function updating($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function updated($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function saving($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function saved($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function deleting($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function deleted($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function restoring($entity);
	
	/**
	 * @param Entity $entity
	 */
	public function restored($entity);
}