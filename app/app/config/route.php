<?php

/**
 * The array name need to be the same as the file name
 * 
 * there are two main groups: 
 *        - auth: the ones which just can be accessed for logged users
 *        - free: the ones which can be accessed whithout bein logged
 * 
 *  The structure is: ['name of the call on get url' => ['http verb' => 'ControllerName@MethodName]  ]
 *  Structure GET is parameters route_name/content/{id}/user/{name}
 */
$route =
    [
        'route' => [
            'auth' => [],
            'anonymous' => [
                'home' => ['GET' => 'HomeController@index'],
                'nfe' => ['GET' => 'NfeController@index'],
                'nfe-import' => ['GET' => 'NfeController@record'],
                'nfe-import-review' => ['POST' => 'NfeController@recordReview'],
                'nfe-save' => ['GET' => 'NfeController@save'],
            ],
        ]
    ];
