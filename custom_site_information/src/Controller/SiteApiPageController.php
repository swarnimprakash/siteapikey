<?php
/**
 * @file
 * Contains \Drupal\custom_site_information\Controller\SiteApiPageController
 */

namespace Drupal\custom_site_information\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Access\AccessResult; 

  

/**
 * Controller for show node json.
 */
class SiteApiPageController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function content(Request $request) {
		$route_match = \Drupal::service('router.no_access_checks')->matchRequest($request);
    $apikey = $route_match['_raw_variables']->get('apikey');
    $nid =  $route_match['_raw_variables']->get('nid');
		$json_array = [
      'data' => []
    ];
		$site_config = \Drupal::config('system.site');
		$site_api_key = $site_config->get('siteapikey');
		
		$node = \Drupal\node\Entity\Node::load($nid);
		if(!empty($node)) {
			$node_id = $node->id();
			$node_type = $node->getType();
			//If apikey matches and note type page then return json else show access denied
			if($site_api_key === $apikey && $node_id == $nid && $node_type == 'page') {
				$json_array['data'][] = [
					'type' => $node_type,
					'id' => $node_id,
					'attributes' => [
						'title' =>  $node->get('title')->value,
						'content' => $node->get('body')->value,
					],
				];
				
				return new JsonResponse($json_array);
			}
			else {
				throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
			}
		}
		else {
			throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
		}
	}
}