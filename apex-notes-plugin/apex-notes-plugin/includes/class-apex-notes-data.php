<?php
/**
 * Apex Notes Data - Cars and Tracks from Le Mans Ultimate
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Data {
    
    /**
     * Get all car classes and cars
     */
    public static function get_cars() {
        return array(
            'hypercar' => array(
                'name' => 'Hypercar',
                'cars' => array(
                    array('id' => 'alpine-a424', 'name' => 'Alpine A424', 'dlc' => true),
                    array('id' => 'aston-martin-valkyrie', 'name' => 'Aston Martin Valkyrie AMR-LMH', 'dlc' => false),
                    array('id' => 'bmw-m-hybrid-v8', 'name' => 'BMW M Hybrid V8', 'dlc' => false),
                    array('id' => 'cadillac-v-series-r', 'name' => 'Cadillac V-Series.R', 'dlc' => false),
                    array('id' => 'ferrari-499p', 'name' => 'Ferrari 499P', 'dlc' => false),
                    array('id' => 'glickenhaus-scg-007', 'name' => 'Glickenhaus SCG 007', 'dlc' => false),
                    array('id' => 'isotta-fraschini-tipo6', 'name' => 'Isotta Fraschini Tipo 6', 'dlc' => true),
                    array('id' => 'lamborghini-sc63', 'name' => 'Lamborghini SC63', 'dlc' => true),
                    array('id' => 'peugeot-9x8', 'name' => 'Peugeot 9X8', 'dlc' => false),
                    array('id' => 'peugeot-9x8-2024', 'name' => 'Peugeot 9X8 2024', 'dlc' => true),
                    array('id' => 'porsche-963', 'name' => 'Porsche 963', 'dlc' => false),
                    array('id' => 'toyota-gr010-hybrid', 'name' => 'Toyota GR010-Hybrid', 'dlc' => false),
                    array('id' => 'vanwall-vandervell-680', 'name' => 'Vanwall Vandervell 680', 'dlc' => false),
                )
            ),
            'lmgt3' => array(
                'name' => 'LMGT3',
                'cars' => array(
                    array('id' => 'ford-mustang-lmgt3', 'name' => 'Ford Mustang LMGT3', 'dlc' => false),
                    array('id' => 'mclaren-720s-lmgt3-evo', 'name' => 'McLaren 720S LMGT3 Evo', 'dlc' => false),
                    array('id' => 'mercedes-amg-lmgt3', 'name' => 'Mercedes-AMG LMGT3', 'dlc' => false),
                    array('id' => 'bmw-m4-lmgt3', 'name' => 'BMW M4 LMGT3', 'dlc' => true),
                    array('id' => 'aston-martin-vantage-lmgt3', 'name' => 'Aston Martin Vantage AMR LMGT3', 'dlc' => true),
                    array('id' => 'corvette-z06-lmgt3-r', 'name' => 'Corvette Z06 LMGT3.R', 'dlc' => true),
                    array('id' => 'ferrari-296-lmgt3', 'name' => 'Ferrari 296 LMGT3', 'dlc' => true),
                    array('id' => 'lamborghini-huracan-lmgt3-evo2', 'name' => 'Lamborghini Huracán LMGT3 Evo2', 'dlc' => true),
                    array('id' => 'lexus-rc-f-lmgt3', 'name' => 'Lexus RC F LMGT3', 'dlc' => true),
                    array('id' => 'porsche-911-gt3-r', 'name' => 'Porsche 911 GT3 R', 'dlc' => true),
                )
            ),
            'lmp2' => array(
                'name' => 'LMP2',
                'cars' => array(
                    array('id' => 'oreca-07-gibson-2023', 'name' => 'ORECA 07 Gibson 2023', 'dlc' => false),
                    array('id' => 'oreca-07-gibson-2024', 'name' => 'ORECA 07 Gibson 2024', 'dlc' => false),
                )
            ),
            'lmp3' => array(
                'name' => 'LMP3',
                'cars' => array(
                    array('id' => 'ginetta-g61-lt-p325-evo', 'name' => 'Ginetta G61-LT-P325-Evo', 'dlc' => true),
                    array('id' => 'ligier-js-p325', 'name' => 'Ligier JS P325', 'dlc' => true),
                )
            ),
            'gte' => array(
                'name' => 'GTE',
                'cars' => array(
                    array('id' => 'aston-martin-vantage-amr', 'name' => 'Aston Martin Vantage AMR', 'dlc' => false),
                    array('id' => 'chevrolet-corvette-c8r', 'name' => 'Chevrolet Corvette C8.R', 'dlc' => false),
                    array('id' => 'ferrari-488-gte-evo', 'name' => 'Ferrari 488 GTE Evo', 'dlc' => false),
                    array('id' => 'porsche-911-rsr-19', 'name' => 'Porsche 911 RSR-19', 'dlc' => false),
                )
            ),
        );
    }
    
    /**
     * Get all tracks with layouts
     */
    public static function get_tracks() {
        return array(
            array(
                'id' => 'bahrain', 
                'name' => 'Bahrain International Circuit', 
                'location' => 'Sakhir, Bahrain', 
                'dlc' => false,
                'layouts' => array('Default', 'Endurance', 'Outer', 'Paddock')
            ),
            array(
                'id' => 'la-sarthe', 
                'name' => 'Circuit de la Sarthe', 
                'location' => 'Le Mans, France', 
                'dlc' => false,
                'layouts' => array('Default', 'Mulsanne')
            ),
            array(
                'id' => 'paul-ricard', 
                'name' => 'Circuit Paul Ricard', 
                'location' => 'Le Castellet, France', 
                'dlc' => true,
                'layouts' => array()
            ),
            array(
                'id' => 'cota', 
                'name' => 'Circuit of the Americas', 
                'location' => 'Austin, USA', 
                'dlc' => true,
                'layouts' => array('Default', 'National')
            ),
            array(
                'id' => 'fuji', 
                'name' => 'Fuji Speedway', 
                'location' => 'Oyama, Japan', 
                'dlc' => false,
                'layouts' => array('Default', 'Classic')
            ),
            array(
                'id' => 'imola', 
                'name' => 'Imola', 
                'location' => 'Imola, Italy', 
                'dlc' => true,
                'layouts' => array()
            ),
            array(
                'id' => 'interlagos', 
                'name' => 'Interlagos', 
                'location' => 'São Paulo, Brazil', 
                'dlc' => true,
                'layouts' => array()
            ),
            array(
                'id' => 'lusail', 
                'name' => 'Lusail International Circuit', 
                'location' => 'Lusail, Qatar', 
                'dlc' => true,
                'layouts' => array('Default', 'Short')
            ),
            array(
                'id' => 'monza', 
                'name' => 'Autodromo Nazionale Monza', 
                'location' => 'Monza, Italy', 
                'dlc' => false,
                'layouts' => array('Default', 'Curva Grande')
            ),
            array(
                'id' => 'portimao', 
                'name' => 'Algarve International Circuit', 
                'location' => 'Portimão, Portugal', 
                'dlc' => false,
                'layouts' => array()
            ),
            array(
                'id' => 'sebring', 
                'name' => 'Sebring International Raceway', 
                'location' => 'Florida, USA', 
                'dlc' => false,
                'layouts' => array('Default', 'School')
            ),
            array(
                'id' => 'silverstone', 
                'name' => 'Silverstone International', 
                'location' => 'Silverstone, UK', 
                'dlc' => true,
                'layouts' => array()
            ),
            array(
                'id' => 'spa', 
                'name' => 'Spa-Francorchamps', 
                'location' => 'Stavelot, Belgium', 
                'dlc' => false,
                'layouts' => array('Default', 'Endurance')
            ),
        );
    }
    
    /**
     * Get layouts for a specific track
     */
    public static function get_track_layouts($track_id) {
        $tracks = self::get_tracks();
        foreach ($tracks as $track) {
            if ($track['id'] === $track_id) {
                return isset($track['layouts']) ? $track['layouts'] : array();
            }
        }
        return array();
    }
    
    /**
     * Get difficulty levels
     */
    public static function get_difficulties() {
        return array(
            array('id' => 'beginner', 'name' => 'Beginner', 'color' => '#22c55e'),
            array('id' => 'intermediate', 'name' => 'Intermediate', 'color' => '#F58220'),
            array('id' => 'advanced', 'name' => 'Advanced', 'color' => '#ef4444'),
            array('id' => 'alien', 'name' => 'Alien', 'color' => '#a855f7'),
        );
    }
    
    /**
     * Get car by ID
     */
    public static function get_car_by_id($car_id) {
        $cars = self::get_cars();
        foreach ($cars as $class_id => $class) {
            foreach ($class['cars'] as $car) {
                if ($car['id'] === $car_id) {
                    $car['class'] = $class_id;
                    $car['class_name'] = $class['name'];
                    return $car;
                }
            }
        }
        return null;
    }
    
    /**
     * Get track by ID
     */
    public static function get_track_by_id($track_id) {
        $tracks = self::get_tracks();
        foreach ($tracks as $track) {
            if ($track['id'] === $track_id) {
                return $track;
            }
        }
        return null;
    }
    
    /**
     * Get difficulty by ID
     */
    public static function get_difficulty_by_id($diff_id) {
        $difficulties = self::get_difficulties();
        foreach ($difficulties as $diff) {
            if ($diff['id'] === $diff_id) {
                return $diff;
            }
        }
        return null;
    }
}
