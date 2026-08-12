<?php

class TimestampBehavior extends ModelBehavior {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'data_cadastro' => date('Y-m-d H:i:s'),
//                    'updated_at' => 'always',
                ],
//                'Orders.completed' => [
//                    'completed_at' => 'always'
//                ]
            ]
        ]);
    }
    
}


/*
 * class OrdersTable extends Table {
    public function initialize(array $config) {
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ],
                'Orders.completed' => [
                    'completed_at' => 'always'
                ]
            ]
        ]);
    }
}
 */