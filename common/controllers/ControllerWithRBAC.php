<?php

namespace common\controllers;

use Yii;
use yii\web\Controller;

class ControllerWithRBAC extends \yii\web\Controller
{
	// Массив действий для которых не проводится проверка на доступ
	public $ignoredActions = false;
	/**
	 * {@inheritdoc}
	 */
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {

			if (is_bool($this->ignoredActions) && $this->ignoredActions) {
				return true;
			}

			if (is_array($this->ignoredActions) && in_array($action->id, $this->ignoredActions)) {
				return true;
			}

			$route = \Yii::$app->requestedRoute;
			
			if (\Yii::$app->user->can('@' . $route) && !\Yii::$app->user->can('!' . $route)) {
				return true;
			} else {
				throw new \yii\web\ForbiddenHttpException('Access denied');
			}
			
		}

		return false;
	}

}
