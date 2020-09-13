<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{

	public function messages()
	{
		$token = $this->request->getVar('token');
		if ($token) {
			$userModel = new \App\Models\UserModel();
			$validToken = $userModel->where(['token' => $token])->first();
			if ($validToken) {
				$messageModel = new \App\Models\MessageModel();
				$messages = $messageModel->orderBy('id', 'ASC')->findAll();
				if ($messages) {
					foreach ($messages as $msg) {
						$getUser = $userModel->find($msg['user_id']);
						$user = [
							'id'		=> intval($getUser['id']),
							'name' 		=> $getUser['name'],
							'email'		=> $getUser['email']
						];

						$data[] = [
							'id' 			=> intval($msg['id']),
							'user'			=> $user,
							'message'		=> $msg['message'],
							'attachment' 	=> $msg['attachment'] ? base_url('/assets/attachments') . '/' . $msg['attachment'] : null,
							'created_at'	=> date('d/m/Y H:i', strtotime($msg['created_at'])),
							'updated_at'	=> date('d/m/Y H:i', strtotime($msg['updated_at']))
						];
					}
				} else {
					$data = [];
				}
				return $this->respond($data);
			} else {
				return $this->failUnauthorized();
			}
		} else {
			return $this->failUnauthorized();
		}
	}

	public function create()
	{
		if ($this->request->getMethod() == 'post') {
			helper(['form']);
			$token = $this->request->getVar('token');
			$message = $this->request->getVar('message');
			if ($token) {
				$userModel = new \App\Models\UserModel();
				$validToken = $userModel->where(['token' => $token])->first();
				if ($validToken) {
					$messageModel = new \App\Models\MessageModel();

					$rules = [
						'message' 			=> ['rules' => 'required', 'label' => 'message']
					];

					if (!$this->validate($rules)) {
						return $this->fail(implode('<br />', $this->validator->getErrors()));
						die;
					}

					$checkFile = dot_array_search('attachment.name', $_FILES);
                    if ($checkFile != '') {
			            $img = [
				            'attachment' => 'uploaded[attachment]|max_size[attachment,2000]|is_image[attachment]'
			            ];
			            $rules = array_merge($rules, $img);

                        $file = $this->request->getFile('attachment');
						if (!$file->isValid()) {
							return $this->fail($file->getErrorString());
							die;
						} else {
							$fileName = $file->getRandomName();
							$file->move('./assets/attachments/', $fileName);
						}
		            }

					$data = [
						'user_id'		=> $validToken['id'],
						'message' 		=> $message,
						'attachment' 	=> (isset($fileName) ? $fileName : null)
					];

					$query = $messageModel->save($data);
					if ($query) {
						return $this->respond(['status' => true]);
					} else {
						return $this->fail('Failed to send message');
					}
				} else {
					return $this->failUnauthorized();
				}
			} else {
				return $this->failUnauthorized();
			}
		} else {
			return $this->fail('Only post method is allowed');
		}
	}

	public function user()
	{
		if ($this->request->getMethod() == 'post') {
			$token = $this->request->getVar('token');
			if ($token) {
				$userModel = new \App\Models\UserModel();
				$validToken = $userModel->where(['token' => $token])->first();
				if ($validToken) {
					$id = $this->request->getVar('id');
					$user = $userModel->find($id);
					if ($user) {
						$data = [
							'id' 			=> $user['id'],
							'name' 			=> $user['name'],
							'email' 		=> $user['email'],
							'created_at' 	=> $user['created_at'],
							'updated_at' 	=> $user['updated_at']
						];
						return $this->respond($data);
					} else {
						return $this->failNotFound();
					}
				} else {
					return $this->failUnauthorized();
				}
			} else {
				return $this->failUnauthorized();
			}
		} else {
			return $this->fail('Only post method is allowed');
		}
	}

	//--------------------------------------------------------------------

}
