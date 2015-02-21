<?php
class TourComponent extends Component {
    var $model;
    var $controller;

    private $tourList = array();
    private $tourGuides;

    public function initialize(Controller $controller) {
        $this->controller = &$controller;

        $this->tourGuides = json_decode($this->controller->Setting->getOption('tourGuides'), true);

        $this->tourList = array(
            'Events' => array(
                'index' => array(
                    'id' => 'events_index',
                    'steps' => array(
                        array(
                            'target' => 'header',
                            'placement' => 'bottom',
                            'title' => __('Welcome to MushRaider'),
                            'content' => __('This page let you view and manage events'),
                            'xOffset' => 'center'
                        ),
                        array(
                            'target' => 'tourAdmin',
                            'placement' => 'bottom',
                            'title' => __('Customize MushRaider'),
                            'content' => __('In the admin panel you can customize MushRaider, like adding your games, create user groups, widgets, appearance...')
                        ),
                        array(
                            'target' => 'tourAccount',
                            'placement' => 'bottom',
                            'title' => __('Manage your account'),
                            'content' => __('Here you can manage your account and create your first character')
                        ),
                        array(
                            'target' => 'createEvent',
                            'placement' => 'bottom',
                            'title' => __('Quick event creation'),
                            'content' => __('Use this form to quickly create events, or use the "+" buttons below')
                        ),
                        array(
                            'target' => 'filterEvents',
                            'placement' => 'bottom',
                            'title' => __('Filter events'),
                            'content' => __('You can filter the games displayed below')
                        ),
                        array(
                            'target' => 'header',
                            'placement' => 'bottom',
                            'title' => __('Thank you for using MushRaider'),
                            'content' => __('Hope you will enjoy this raid planner, if you encounter any issue or have suggestions please visits the official forum <a href="http://forum.raidhead.com" target="_blank">http://forum.raidhead.com</a>'),
                            'xOffset' => 'center'
                        )
                    )
                )
            )
        );
    }

    public function startup(Controller $controller) {
        $this->controller = &$controller;

        if($this->controller->user && $this->controller->user['User']['can']['full_permissions']) {
            if(!empty($this->tourList[$this->controller->name]) && !empty($this->tourList[$this->controller->name][$this->controller->action])) {
                $tourName = $this->controller->name.'_'.$this->controller->action.'_'.$this->controller->user['User']['id'];
                if(empty($this->tourGuides[$tourName])) {
                    $this->tourGuides[$tourName] = true;
                    $this->controller->Setting->setOption('tourGuides', json_encode($this->tourGuides));
                    $this->controller->set('tourGuide', json_encode($this->tourList[$this->controller->name][$this->controller->action]));
                }
            }
        }
    }
}