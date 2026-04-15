<?php
/**
 * Apex Notes Track Encyclopedia
 * Comprehensive database of motorsport circuits worldwide
 * Data sourced from public racing circuit information
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Tracks {
    
    /**
     * Get all tracks data
     */
    public static function get_all_tracks() {
        return array(
            // ============================================
            // FORMULA 1 CIRCUITS (Current Calendar)
            // ============================================
            'spa-francorchamps' => array(
                'name' => 'Spa-Francorchamps',
                'country' => 'Belgium',
                'region' => 'Europe',
                'location' => 'Stavelot, Wallonia',
                'length_km' => 7.004,
                'length_miles' => 4.352,
                'turns' => 19,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1921,
                'architect' => 'Jules de Their, Henri Langlois Van Ophem',
                'fia_grade' => '1',
                'lap_record' => '1:46.286 (Valtteri Bottas, 2018)',
                'capacity' => 70000,
                'events' => array('Formula 1 Belgian GP', '24 Hours of Spa', 'WEC 6 Hours'),
                'famous_corners' => array(
                    'La Source' => 'Hairpin at the start, Turn 1',
                    'Eau Rouge' => 'Iconic uphill left-right-left complex after La Source, Turns 2-4',
                    'Raidillon' => 'The steep climb following Eau Rouge',
                    'Les Combes' => 'Fast chicane complex, Turns 5-7',
                    'Bruxelles' => 'Left-hander leading to the back section',
                    'Pouhon' => 'Double-apex left-hander, very fast, Turns 10-11',
                    'Fagnes' => 'Fast chicane',
                    'Stavelot' => 'Double right-hander',
                    'Blanchimont' => 'Flat-out left kink before Bus Stop, very fast',
                    'Bus Stop' => 'Tight chicane before pit straight'
                ),
                'elevation_change' => 102,
                'description' => 'One of the most beloved circuits in motorsport, Spa-Francorchamps is known for its challenging layout, unpredictable weather, and the legendary Eau Rouge/Raidillon complex. The circuit weaves through the Ardennes forest with significant elevation changes.',
                'history' => 'Originally a street circuit using public roads, it was redesigned in 1979 to create the modern permanent circuit. The track is famous for its unpredictable microclimate where it can rain on one part while being dry on another.',
                'coordinates' => array('lat' => 50.4372, 'lng' => 5.9714)
            ),
            
            'silverstone' => array(
                'name' => 'Silverstone Circuit',
                'country' => 'United Kingdom',
                'region' => 'Europe',
                'location' => 'Silverstone, Northamptonshire',
                'length_km' => 5.891,
                'length_miles' => 3.660,
                'turns' => 18,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1948,
                'architect' => 'RAC, later Hermann Tilke (2010 modifications)',
                'fia_grade' => '1',
                'lap_record' => '1:27.097 (Max Verstappen, 2020)',
                'capacity' => 150000,
                'events' => array('Formula 1 British GP', 'MotoGP British GP', 'Silverstone Classic'),
                'famous_corners' => array(
                    'Abbey' => 'Fast right-hander, Turn 1',
                    'Village' => 'Tight left-hand complex',
                    'The Loop' => 'Hairpin section',
                    'Wellington Straight' => 'Short straight before Brooklands',
                    'Brooklands' => 'Slow left-hander',
                    'Luffield' => 'Long right-hand corner',
                    'Copse' => 'High-speed right-hander, one of the fastest corners in F1',
                    'Maggotts' => 'Part of famous esses section',
                    'Becketts' => 'Continuation of high-speed esses',
                    'Chapel' => 'Final part of the esses complex',
                    'Hangar Straight' => 'Long straight leading to Stowe',
                    'Stowe' => 'Fast right-hander',
                    'Club' => 'Final corner before pit straight'
                ),
                'elevation_change' => 22,
                'description' => 'The home of British motorsport, Silverstone hosted the first-ever Formula 1 World Championship race in 1950. The circuit is known for its fast, flowing nature and the challenging Maggotts-Becketts-Chapel complex.',
                'history' => 'Built on a former RAF bomber station, Silverstone has been the permanent home of the British Grand Prix since 1987. Major redesigns in 1991 and 2010 created the current layout with the Wing pit complex.',
                'coordinates' => array('lat' => 52.0786, 'lng' => -1.0169)
            ),
            
            'monza' => array(
                'name' => 'Autodromo Nazionale Monza',
                'country' => 'Italy',
                'region' => 'Europe',
                'location' => 'Monza, Lombardy',
                'length_km' => 5.793,
                'length_miles' => 3.600,
                'turns' => 11,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1922,
                'architect' => 'Alfredo Rosselli',
                'fia_grade' => '1',
                'lap_record' => '1:21.046 (Rubens Barrichello, 2004)',
                'capacity' => 113000,
                'events' => array('Formula 1 Italian GP', 'Monza 1000km'),
                'famous_corners' => array(
                    'Prima Variante' => 'First chicane after start/finish, Turns 1-2',
                    'Curva Biassono' => 'Right-hander, Turn 3',
                    'Seconda Variante' => 'Second chicane, Turns 4-5',
                    'Variante della Roggia' => 'Also known as the Lesmos, Turns 6-7',
                    'Curva del Serraglio' => 'Right-hand bend',
                    'Variante Ascari' => 'Fast chicane complex, Turns 8-9-10',
                    'Curva Parabolica' => 'Famous final corner, long right-hander leading to main straight, now called Alboreto'
                ),
                'elevation_change' => 11,
                'description' => 'The "Temple of Speed" - Monza is the fastest circuit on the F1 calendar with cars reaching over 350 km/h on the long straights. Located in a royal park north of Milan.',
                'history' => 'One of the oldest purpose-built racing circuits in the world, opened in 1922. The original layout included a high-speed oval banking, parts of which still exist but are no longer used. The Tifosi fans create an incredible atmosphere.',
                'coordinates' => array('lat' => 45.6156, 'lng' => 9.2811)
            ),
            
            'suzuka' => array(
                'name' => 'Suzuka International Racing Course',
                'country' => 'Japan',
                'region' => 'Asia',
                'location' => 'Suzuka, Mie Prefecture',
                'length_km' => 5.807,
                'length_miles' => 3.608,
                'turns' => 18,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1962,
                'architect' => 'John Hugenholtz',
                'fia_grade' => '1',
                'lap_record' => '1:30.983 (Lewis Hamilton, 2019)',
                'capacity' => 155000,
                'events' => array('Formula 1 Japanese GP', 'Suzuka 8 Hours', 'Super GT'),
                'famous_corners' => array(
                    'First Turn' => 'Fast right-left sequence after start',
                    'S Curves' => 'Iconic high-speed esses, Turns 3-6',
                    'Dunlop Curve' => 'Fast left-hander',
                    'Degner Curves' => 'Double right-hander named after Ernst Degner, Turns 8-9',
                    'Hairpin' => 'Tight hairpin, good overtaking spot',
                    'Spoon Curve' => 'Long double-apex left-hander, Turns 13-14',
                    '130R' => 'Legendary high-speed left-hander, originally taken at 130 mph',
                    'Casio Triangle' => 'Final chicane before pit straight'
                ),
                'elevation_change' => 40,
                'description' => 'The only figure-8 circuit in Formula 1, where the track crosses over itself via a bridge. Suzuka is considered one of the greatest driver circuits due to its flowing nature and challenging corners.',
                'history' => 'Built by Honda as a test track, it became an F1 venue in 1987. The circuit has seen many championship-deciding races and dramatic moments. The figure-8 layout is unique in top-level motorsport.',
                'coordinates' => array('lat' => 34.8431, 'lng' => 136.5406)
            ),
            
            'monaco' => array(
                'name' => 'Circuit de Monaco',
                'country' => 'Monaco',
                'region' => 'Europe',
                'location' => 'Monte Carlo',
                'length_km' => 3.337,
                'length_miles' => 2.074,
                'turns' => 19,
                'direction' => 'Clockwise',
                'type' => 'Street Circuit',
                'status' => 'Active',
                'opened' => 1929,
                'architect' => 'Antony Noghès',
                'fia_grade' => '1',
                'lap_record' => '1:12.909 (Lewis Hamilton, 2021)',
                'capacity' => 37000,
                'events' => array('Formula 1 Monaco GP', 'Monaco Historic GP', 'Formula E Monaco ePrix'),
                'famous_corners' => array(
                    'Sainte Dévote' => 'First corner, tight right-hander uphill, Turn 1',
                    'Beau Rivage' => 'Uphill straight after Sainte Dévote',
                    'Massenet' => 'Left-hander at the top of the hill',
                    'Casino Square' => 'Famous landmark section',
                    'Mirabeau' => 'Downhill right-hander, good overtaking spot',
                    'Loews Hairpin' => 'Tightest corner in F1, also called the Grand Hotel hairpin',
                    'Portier' => 'Right-hander leading to tunnel',
                    'Tunnel' => 'Dark tunnel section, dramatic light change',
                    'Nouvelle Chicane' => 'Chicane at the harbor',
                    'Tabac' => 'Fast left-hander by the harbor',
                    'Swimming Pool' => 'Fast chicane complex',
                    'La Rascasse' => 'Tight hairpin near end of lap',
                    'Anthony Noghès' => 'Final corner before pit straight'
                ),
                'elevation_change' => 42,
                'description' => 'The crown jewel of motorsport, Monaco is the most prestigious race on the F1 calendar. Racing through the streets of the principality with barriers inches from the cars, it demands absolute precision.',
                'history' => 'First raced in 1929, the Monaco Grand Prix has been held almost continuously since. The tight streets make overtaking nearly impossible, making qualifying and strategy crucial. It is considered one of the Triple Crown of Motorsport.',
                'coordinates' => array('lat' => 43.7347, 'lng' => 7.4206)
            ),
            
            'interlagos' => array(
                'name' => 'Autódromo José Carlos Pace (Interlagos)',
                'country' => 'Brazil',
                'region' => 'South America',
                'location' => 'São Paulo',
                'length_km' => 4.309,
                'length_miles' => 2.677,
                'turns' => 15,
                'direction' => 'Counter-clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1940,
                'architect' => 'Louis Romero Sanson',
                'fia_grade' => '1',
                'lap_record' => '1:10.540 (Valtteri Bottas, 2018)',
                'capacity' => 60000,
                'events' => array('Formula 1 São Paulo GP', 'WEC 6 Hours', 'Stock Car Brasil'),
                'famous_corners' => array(
                    'Senna S' => 'Downhill esses named after Ayrton Senna, Turns 1-3',
                    'Curva do Sol' => 'Sun Curve, long left-hander',
                    'Reta Oposta' => 'Back straight',
                    'Descida do Lago' => 'Descent to the lake, downhill section',
                    'Ferradura' => 'Horseshoe, hairpin complex',
                    'Laranjinha' => 'Little Orange, tight corner',
                    'Pinheirinho' => 'Little Pine Tree corner',
                    'Bico de Pato' => 'Duck\'s Beak, complex section',
                    'Mergulho' => 'The Dive, fast downhill section',
                    'Junção' => 'Junction, final corner leading to pit straight'
                ),
                'elevation_change' => 41,
                'description' => 'A fan-favorite circuit that often produces dramatic races. The anti-clockwise layout and elevation changes create unique challenges. Named after Brazilian F1 driver Carlos Pace.',
                'history' => 'Originally much longer at 7.96 km, the circuit was shortened in 1990 to its current layout. The passionate Brazilian fans and unpredictable weather often create memorable race weekends.',
                'coordinates' => array('lat' => -23.7036, 'lng' => -46.6997)
            ),
            
            'le-mans' => array(
                'name' => 'Circuit de la Sarthe (Le Mans)',
                'country' => 'France',
                'region' => 'Europe',
                'location' => 'Le Mans, Sarthe',
                'length_km' => 13.626,
                'length_miles' => 8.467,
                'turns' => 38,
                'direction' => 'Clockwise',
                'type' => 'Semi-Permanent',
                'status' => 'Active',
                'opened' => 1923,
                'architect' => 'Various over the years',
                'fia_grade' => '2',
                'lap_record' => '3:14.791 (Kamui Kobayashi, 2017)',
                'capacity' => 263500,
                'events' => array('24 Hours of Le Mans', 'Le Mans 24 Hours Moto', 'Le Mans Classic'),
                'famous_corners' => array(
                    'Dunlop Chicane' => 'First chicane after pit straight',
                    'Dunlop Curve' => 'Right-hand curve',
                    'Esses' => 'Fast S-bend section',
                    'Tertre Rouge' => 'Famous right-hander onto Mulsanne Straight',
                    'Mulsanne Straight' => 'Legendary 6km straight (now with chicanes)',
                    'Mulsanne Corner' => 'Tight right-hander at end of long straight',
                    'Indianapolis' => 'Right-hand complex',
                    'Arnage' => 'Slow right-hander',
                    'Porsche Curves' => 'Fast, sweeping series of bends',
                    'Maison Blanche' => 'Chicane before Ford Chicanes',
                    'Ford Chicanes' => 'Final chicanes before pit straight'
                ),
                'elevation_change' => 31,
                'description' => 'The most famous endurance racing circuit in the world, home to the legendary 24 Hours of Le Mans. The circuit uses both permanent racing facilities and closed public roads.',
                'history' => 'The 24 Hours of Le Mans has been held since 1923, making it the oldest active sports car race in endurance racing. Chicanes were added to the Mulsanne Straight in 1990 to reduce speeds after reaching nearly 400 km/h.',
                'coordinates' => array('lat' => 47.9564, 'lng' => 0.2242)
            ),
            
            'nurburgring' => array(
                'name' => 'Nürburgring',
                'country' => 'Germany',
                'region' => 'Europe',
                'location' => 'Nürburg, Rhineland-Palatinate',
                'length_km' => 5.148,
                'length_miles' => 3.199,
                'turns' => 16,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1984,
                'architect' => 'NÜRBURGRING GmbH (GP circuit)',
                'fia_grade' => '1',
                'lap_record' => '1:29.468 (Michael Schumacher, 2004)',
                'capacity' => 75000,
                'events' => array('24 Hours Nürburgring', 'DTM', 'various FIA events'),
                'famous_corners' => array(
                    'Turn 1' => 'First corner after pit straight',
                    'Mercedes Arena' => 'Stadium section with tight corners',
                    'Veedol Chicane' => 'Chicane complex',
                    'Coca-Cola Kurve' => 'Right-hand hairpin',
                    'NGK Chicane' => 'Chicane leading to back section',
                    'RTL-Kurve' => 'Right-hander'
                ),
                'elevation_change' => 55,
                'description' => 'The modern GP circuit at the Nürburgring complex. Not to be confused with the legendary Nordschleife (North Loop).',
                'history' => 'Built in 1984 after F1 declared the Nordschleife too dangerous. The GP circuit hosted the European, Luxembourg, and German Grands Prix at various times.',
                'coordinates' => array('lat' => 50.3356, 'lng' => 6.9475),
                'nordschleife' => array(
                    'name' => 'Nürburgring Nordschleife',
                    'length_km' => 20.832,
                    'length_miles' => 12.944,
                    'turns' => 154,
                    'nickname' => 'The Green Hell',
                    'lap_record' => '5:19.55 (Stefan Bellof, 1983, F1 qualifying)',
                    'description' => 'The legendary "Green Hell" - one of the most demanding racing circuits ever built. Features massive elevation changes, blind crests, and 154 corners through the Eifel mountains.'
                )
            ),
            
            'bahrain' => array(
                'name' => 'Bahrain International Circuit',
                'country' => 'Bahrain',
                'region' => 'Middle East',
                'location' => 'Sakhir',
                'length_km' => 5.412,
                'length_miles' => 3.363,
                'turns' => 15,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 2004,
                'architect' => 'Hermann Tilke',
                'fia_grade' => '1',
                'lap_record' => '1:31.447 (Pedro de la Rosa, 2005)',
                'capacity' => 70000,
                'events' => array('Formula 1 Bahrain GP', 'WEC 8 Hours'),
                'famous_corners' => array(
                    'Turn 1' => 'First corner, tight right-hander',
                    'Turn 4' => 'Hairpin, good overtaking opportunity',
                    'Turn 10' => 'Fast right-hander',
                    'Turn 11-12-13' => 'Tight complex leading to final sector',
                    'Turn 14' => 'Final corner before pit straight'
                ),
                'elevation_change' => 17,
                'description' => 'The first F1 circuit in the Middle East, set in the desert with challenging sand conditions. Features multiple layout configurations.',
                'history' => 'Opened in 2004 as part of Bahrain\'s push into motorsport. Has hosted several dramatic night races and was the site of Romain Grosjean\'s miraculous escape from a fiery crash in 2020.',
                'coordinates' => array('lat' => 26.0325, 'lng' => 50.5106)
            ),
            
            'cota' => array(
                'name' => 'Circuit of the Americas',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Austin, Texas',
                'length_km' => 5.513,
                'length_miles' => 3.426,
                'turns' => 20,
                'direction' => 'Counter-clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 2012,
                'architect' => 'Hermann Tilke',
                'fia_grade' => '1',
                'lap_record' => '1:36.169 (Charles Leclerc, 2019)',
                'capacity' => 120000,
                'events' => array('Formula 1 US GP', 'MotoGP Americas GP', 'NASCAR'),
                'famous_corners' => array(
                    'Turn 1' => 'Steep uphill 40m climb to first corner',
                    'Esses (2-6)' => 'High-speed S-curves inspired by Silverstone\'s Maggotts-Becketts',
                    'Turn 11' => 'Hairpin, main overtaking zone',
                    'Turn 12-15' => 'Stadium section with multiple tight corners',
                    'Turn 16-18' => 'Triple-apex corner complex',
                    'Turn 19-20' => 'Final turns before pit straight'
                ),
                'elevation_change' => 41,
                'description' => 'America\'s purpose-built F1 circuit featuring a dramatic uphill run to Turn 1 and sections inspired by famous corners from around the world.',
                'history' => 'Opened in 2012, ending a long absence of a purpose-built F1 venue in the USA. The Turn 1 climb offers stunning views of the Austin skyline.',
                'coordinates' => array('lat' => 30.1328, 'lng' => -97.6411)
            ),
            
            'imola' => array(
                'name' => 'Autodromo Enzo e Dino Ferrari (Imola)',
                'country' => 'Italy',
                'region' => 'Europe',
                'location' => 'Imola, Emilia-Romagna',
                'length_km' => 4.909,
                'length_miles' => 3.050,
                'turns' => 19,
                'direction' => 'Counter-clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1953,
                'architect' => 'Umberto Nasi',
                'fia_grade' => '1',
                'lap_record' => '1:15.484 (Lewis Hamilton, 2020)',
                'capacity' => 78000,
                'events' => array('Formula 1 Emilia Romagna GP', 'WSBK', 'DTM'),
                'famous_corners' => array(
                    'Tamburello' => 'Site of Senna\'s fatal crash, now a chicane',
                    'Villeneuve' => 'Named after Gilles Villeneuve, chicane',
                    'Tosa' => 'Long left-hander',
                    'Piratella' => 'Uphill left-right sequence',
                    'Acque Minerali' => 'Downhill chicane leading to straight',
                    'Variante Alta' => 'High chicane',
                    'Rivazza' => 'Two left-handers through the park'
                ),
                'elevation_change' => 41,
                'description' => 'A classic Italian circuit with a rich but tragic history. Known for its flowing layout and passionate fans. Named after Enzo and his son Dino Ferrari.',
                'history' => 'Forever associated with the tragic 1994 weekend when Ayrton Senna and Roland Ratzenberger lost their lives. The circuit was heavily modified afterward to improve safety.',
                'coordinates' => array('lat' => 44.3439, 'lng' => 11.7167)
            ),
            
            'portimao' => array(
                'name' => 'Autódromo Internacional do Algarve',
                'country' => 'Portugal',
                'region' => 'Europe',
                'location' => 'Portimão, Algarve',
                'length_km' => 4.653,
                'length_miles' => 2.891,
                'turns' => 15,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 2008,
                'architect' => 'Ricardo Pina',
                'fia_grade' => '1',
                'lap_record' => '1:18.750 (Lewis Hamilton, 2020)',
                'capacity' => 100000,
                'events' => array('MotoGP Portuguese GP', 'WEC', 'F1 Portuguese GP (2020-2021)'),
                'famous_corners' => array(
                    'Turn 1' => 'Downhill right-hander',
                    'Turn 3' => 'Uphill blind crest',
                    'Turn 5' => 'Downhill left-hander with elevation drop',
                    'Turn 8' => 'Fast right-hander',
                    'Turn 11' => 'Hairpin',
                    'Turn 14-15' => 'Final sequence with elevation change'
                ),
                'elevation_change' => 60,
                'description' => 'A rollercoaster of a circuit with dramatic elevation changes and blind crests. Became an F1 venue during COVID calendar changes and was well-received.',
                'history' => 'Opened in 2008, the circuit gained worldwide attention when it joined the F1 calendar in 2020. Known for its challenging undulations.',
                'coordinates' => array('lat' => 37.2270, 'lng' => -8.6267)
            ),
            
            'fuji' => array(
                'name' => 'Fuji Speedway',
                'country' => 'Japan',
                'region' => 'Asia',
                'location' => 'Oyama, Shizuoka',
                'length_km' => 4.563,
                'length_miles' => 2.835,
                'turns' => 16,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1966,
                'architect' => 'Hermann Tilke (2005 redesign)',
                'fia_grade' => '1',
                'lap_record' => '1:18.426 (Kimi Räikkönen, 2008)',
                'capacity' => 140000,
                'events' => array('WEC 6 Hours of Fuji', 'Super GT', 'Super Formula'),
                'famous_corners' => array(
                    'TGR Corner' => 'First corner after long pit straight',
                    'Coca-Cola Corner' => 'Hairpin',
                    'Toyota Corner' => 'Complex section',
                    'Dunlop Corner' => 'Fast right-hander',
                    'Netz Corner' => 'Final corner onto pit straight',
                    'Pit Straight' => 'One of the longest straights in motorsport at 1.475km'
                ),
                'elevation_change' => 35,
                'description' => 'Set at the base of Mount Fuji with spectacular views of the iconic volcano. Features one of the longest straights in motorsport.',
                'history' => 'Originally featured a high-speed 30-degree banked turn. Redesigned by Hermann Tilke in 2005, it has hosted F1, WEC, and various Japanese championships.',
                'coordinates' => array('lat' => 35.3722, 'lng' => 138.9278)
            ),
            
            'sebring' => array(
                'name' => 'Sebring International Raceway',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Sebring, Florida',
                'length_km' => 6.019,
                'length_miles' => 3.740,
                'turns' => 17,
                'direction' => 'Clockwise',
                'type' => 'Permanent/Airfield Circuit',
                'status' => 'Active',
                'opened' => 1950,
                'architect' => 'Alec Ulmann (original layout)',
                'fia_grade' => '2',
                'lap_record' => '1:46.697 (Helio Castroneves, 2022)',
                'capacity' => 50000,
                'events' => array('12 Hours of Sebring', 'WEC 1000 Miles of Sebring'),
                'famous_corners' => array(
                    'Turn 1' => 'Fast right-hander',
                    'Sunset Bend' => 'Long left-hand sweeper',
                    'Turn 7' => 'Hairpin',
                    'Turn 10' => 'Fast right onto back straight',
                    'Turn 13' => 'Tower Turn',
                    'Turn 17' => 'Final corner, bumpy transition from concrete to asphalt'
                ),
                'elevation_change' => 5,
                'description' => 'One of America\'s oldest permanent racing circuits, famous for its notoriously bumpy surface combining concrete and asphalt sections from its airfield origins.',
                'history' => 'Built on a WWII airfield, the 12 Hours of Sebring is one of the most prestigious endurance races in the world, forming part of the Triple Crown of Endurance Racing.',
                'coordinates' => array('lat' => 27.4544, 'lng' => -81.3486)
            ),
            
            'lusail' => array(
                'name' => 'Lusail International Circuit',
                'country' => 'Qatar',
                'region' => 'Middle East',
                'location' => 'Lusail',
                'length_km' => 5.419,
                'length_miles' => 3.367,
                'turns' => 16,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 2004,
                'architect' => 'Tilke Engineers',
                'fia_grade' => '1',
                'lap_record' => '1:24.319 (Max Verstappen, 2023)',
                'capacity' => 8000,
                'events' => array('Formula 1 Qatar GP', 'MotoGP Qatar GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Long right-hander',
                    'Turn 6' => 'Hairpin',
                    'Turn 12-13' => 'Fast chicane',
                    'Turn 16' => 'Final corner leading to pit straight'
                ),
                'elevation_change' => 2,
                'description' => 'A modern circuit in the desert that hosts the spectacular night-lit MotoGP and F1 races. Known for its flowing layout and high-speed sections.',
                'history' => 'Opened in 2004, it became the first MotoGP venue with permanent floodlighting for night racing. Joined the F1 calendar in 2021.',
                'coordinates' => array('lat' => 25.4900, 'lng' => 51.4542)
            ),
            
            // ============================================
            // LEGENDARY CIRCUITS
            // ============================================
            'laguna-seca' => array(
                'name' => 'WeatherTech Raceway Laguna Seca',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Monterey, California',
                'length_km' => 3.602,
                'length_miles' => 2.238,
                'turns' => 11,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1957,
                'architect' => 'General Frank Gruver',
                'fia_grade' => '2',
                'lap_record' => '1:05.786 (Josef Newgarden, 2019, IndyCar)',
                'capacity' => 32000,
                'events' => array('IndyCar', 'IMSA', 'MotoGP Americas'),
                'famous_corners' => array(
                    'Turn 1' => 'Andretti Hairpin',
                    'Turn 2-3' => 'Uphill section',
                    'Turn 5' => 'Fast right-hander',
                    'Turn 6' => 'The Corkscrew entrance - blind left',
                    'Turn 8' => 'The Corkscrew exit - iconic downhill left-right with 5-story drop',
                    'Turn 8A' => 'Rainey Curve',
                    'Turn 9' => 'Fast right-hand sweeper',
                    'Turn 11' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 55,
                'description' => 'Famous for "The Corkscrew" - one of motorsport\'s most iconic corners. The blind entry and dramatic elevation drop make it unique in world motorsport.',
                'history' => 'Built on a dry lake bed, the circuit has hosted top-level racing since 1957. The Corkscrew has seen legendary moments including Valentino Rossi\'s pass on Casey Stoner in MotoGP 2008.',
                'coordinates' => array('lat' => 36.5842, 'lng' => -121.7533)
            ),
            
            'brands-hatch' => array(
                'name' => 'Brands Hatch',
                'country' => 'United Kingdom',
                'region' => 'Europe',
                'location' => 'Fawkham, Kent',
                'length_km' => 3.916,
                'length_miles' => 2.433,
                'turns' => 9,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1950,
                'architect' => 'John Hall',
                'fia_grade' => '2',
                'lap_record' => '1:09.593 (Nigel Mansell, 1986)',
                'capacity' => 120000,
                'events' => array('BTCC', 'DTM', 'British GT'),
                'famous_corners' => array(
                    'Paddock Hill Bend' => 'Dramatic downhill right-hander, one of the most famous corners in the UK',
                    'Druids' => 'Hairpin at the top of the hill',
                    'Graham Hill Bend' => 'Long right-hander',
                    'Surtees' => 'Downhill section on GP circuit',
                    'Hawthorn Bend' => 'Fast right-hander on GP circuit',
                    'Westfield' => 'Section on GP circuit',
                    'Sheene Curve' => 'Fast curve named after Barry Sheene',
                    'Stirlings' => 'Bend before Clearways',
                    'Clearways' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 40,
                'description' => 'A natural amphitheatre providing excellent spectator views. Paddock Hill Bend is one of the most spectacular corners in motorsport with its dramatic downhill plunge.',
                'history' => 'Started as a grass track for cyclists and bikes in 1926. Has hosted 14 Formula 1 British and European Grands Prix. Known for producing dramatic racing.',
                'coordinates' => array('lat' => 51.3569, 'lng' => 0.2628)
            ),
            
            'watkins-glen' => array(
                'name' => 'Watkins Glen International',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Watkins Glen, New York',
                'length_km' => 5.430,
                'length_miles' => 3.374,
                'turns' => 11,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1956,
                'architect' => 'Bill Milliken',
                'fia_grade' => '2',
                'lap_record' => '1:30.958 (Helio Castroneves, 2018)',
                'capacity' => 41000,
                'events' => array('NASCAR', 'IMSA WeatherTech', 'Sahlen\'s 6 Hours'),
                'famous_corners' => array(
                    'Turn 1' => '90-degree right',
                    'The Esses' => 'High-speed S-curves, Turns 2-5',
                    'Toe of the Boot' => 'Turn 6',
                    'Heel of the Boot' => 'Turns 7-8',
                    'Back Straight' => 'Leading to Turn 9',
                    'Turn 10' => 'Left-hander',
                    'Turn 11' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 50,
                'description' => 'One of America\'s finest natural terrain road courses, set in the beautiful Finger Lakes region. Known for its flowing high-speed layout.',
                'history' => 'Hosted F1 from 1961-1980 and has been a fixture of American motorsport since. The Six Hours endurance race is a highlight of the IMSA calendar.',
                'coordinates' => array('lat' => 42.3369, 'lng' => -76.9275)
            ),
            
            'zandvoort' => array(
                'name' => 'Circuit Zandvoort',
                'country' => 'Netherlands',
                'region' => 'Europe',
                'location' => 'Zandvoort, North Holland',
                'length_km' => 4.259,
                'length_miles' => 2.646,
                'turns' => 14,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1948,
                'architect' => 'S. de Jong (Tilke redesigned 2020)',
                'fia_grade' => '1',
                'lap_record' => '1:11.097 (Lewis Hamilton, 2021)',
                'capacity' => 105000,
                'events' => array('Formula 1 Dutch GP', 'DTM'),
                'famous_corners' => array(
                    'Tarzanbocht' => 'Turn 1, heavily banked at 18 degrees',
                    'Gerlachbocht' => 'Turn 2',
                    'Hugenholzbocht' => 'Named after circuit designer John Hugenholtz',
                    'Scheivlak' => 'Turn 7, banked at 19%',
                    'Mastersbocht' => 'Fast right-hander',
                    'Bocht 11' => 'Turn 11',
                    'Arie Luyendykbocht' => 'Final corner, banked at 18 degrees for DRS overtaking'
                ),
                'elevation_change' => 17,
                'description' => 'A classic circuit modernized for F1\'s return in 2021 with the addition of two banked corners to promote overtaking. Set in the dunes near the North Sea coast.',
                'history' => 'Hosted F1 from 1952-1985 before funding issues forced closure from the calendar. Major updates including banking at Turns 1 and 14 allowed F1 to return in 2021 to support Max Verstappen.',
                'coordinates' => array('lat' => 52.3889, 'lng' => 4.5408)
            ),
            
            'red-bull-ring' => array(
                'name' => 'Red Bull Ring',
                'country' => 'Austria',
                'region' => 'Europe',
                'location' => 'Spielberg, Styria',
                'length_km' => 4.318,
                'length_miles' => 2.683,
                'turns' => 10,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1969,
                'architect' => 'Ernst Fuchs (original), Tilke (redesign)',
                'fia_grade' => '1',
                'lap_record' => '1:05.619 (Carlos Sainz Jr., 2020)',
                'capacity' => 100000,
                'events' => array('Formula 1 Austrian GP', 'MotoGP Styrian GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Niki Lauda Kurve - Uphill right-hander',
                    'Turn 2' => 'Remus Kurve - Blind apex uphill',
                    'Turn 3' => 'Schlossgold Kurve - Downhill right',
                    'Turn 4' => 'Rauch Kurve - Tight right-hand hairpin',
                    'Turn 6' => 'Wurth Kurve - Left-hander',
                    'Turn 7' => 'Rindt Kurve - Right-hander leading to back straight',
                    'Turn 9-10' => 'Final complex with tight chicane'
                ),
                'elevation_change' => 65,
                'description' => 'A short but spectacular circuit set in the Styrian mountains with dramatic elevation changes. Known for its stunning scenery and modern facilities.',
                'history' => 'Originally called Österreichring, it was shortened and renamed A1-Ring in 1996, then became Red Bull Ring in 2011 after purchase by Red Bull. The original circuit was much longer and faster.',
                'coordinates' => array('lat' => 47.2197, 'lng' => 14.7647)
            ),
            
            'hungaroring' => array(
                'name' => 'Hungaroring',
                'country' => 'Hungary',
                'region' => 'Europe',
                'location' => 'Mogyoród, near Budapest',
                'length_km' => 4.381,
                'length_miles' => 2.722,
                'turns' => 14,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1986,
                'architect' => 'Patrick Haighton',
                'fia_grade' => '1',
                'lap_record' => '1:16.627 (Lewis Hamilton, 2020)',
                'capacity' => 120000,
                'events' => array('Formula 1 Hungarian GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Tight right-hander downhill',
                    'Turn 2' => 'Left-hander',
                    'Turn 3' => 'Long right-hander',
                    'Turn 4' => 'Uphill chicane',
                    'Turn 11' => 'Hairpin, main overtaking spot',
                    'Turn 14' => 'Final corner leading to pit straight'
                ),
                'elevation_change' => 36,
                'description' => 'Known as "Monaco without the walls" due to its tight, twisty nature that makes overtaking very difficult. Set in a natural valley.',
                'history' => 'The first F1 race behind the Iron Curtain when it hosted the 1986 Hungarian GP. Has produced many dramatic races despite its difficult-to-pass layout.',
                'coordinates' => array('lat' => 47.5789, 'lng' => 19.2486)
            ),
            
            'mugello' => array(
                'name' => 'Autodromo Internazionale del Mugello',
                'country' => 'Italy',
                'region' => 'Europe',
                'location' => 'Scarperia e San Piero, Tuscany',
                'length_km' => 5.245,
                'length_miles' => 3.259,
                'turns' => 15,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1974,
                'architect' => 'Gianfranco Agnelli',
                'fia_grade' => '1',
                'lap_record' => '1:18.740 (Lewis Hamilton, 2020)',
                'capacity' => 50000,
                'events' => array('MotoGP Italian GP', 'Ferrari Challenge'),
                'famous_corners' => array(
                    'San Donato' => 'Turn 1, sweeping right-hander',
                    'Luco' => 'Turn 2',
                    'Poggio Secco' => 'Turn 3',
                    'Materassi' => 'Turn 4',
                    'Borgo San Lorenzo' => 'Turn 5-6 chicane',
                    'Casanova-Savelli' => 'Iconic uphill esses, Turns 7-8',
                    'Arrabbiata 1 & 2' => 'High-speed corners, Turns 9-10',
                    'Scarperia' => 'Turn 11-12',
                    'Correntaio' => 'Turn 13',
                    'Biondetti' => 'Final turns 14-15'
                ),
                'elevation_change' => 41,
                'description' => 'Ferrari\'s private test track set in the stunning Tuscan hills. Known for its high-speed, flowing nature and challenging undulations.',
                'history' => 'Owned by Ferrari since 1988, it primarily serves as the team\'s test track. Hosted its first F1 race in 2020 (Ferrari\'s 1000th GP) and is a beloved MotoGP venue.',
                'coordinates' => array('lat' => 43.9975, 'lng' => 11.3719)
            ),
            
            'sepang' => array(
                'name' => 'Sepang International Circuit',
                'country' => 'Malaysia',
                'region' => 'Asia',
                'location' => 'Sepang, Selangor',
                'length_km' => 5.543,
                'length_miles' => 3.444,
                'turns' => 15,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1999,
                'architect' => 'Hermann Tilke',
                'fia_grade' => '1',
                'lap_record' => '1:34.080 (Sebastian Vettel, 2017)',
                'capacity' => 130000,
                'events' => array('MotoGP Malaysian GP', 'Asian Le Mans Series'),
                'famous_corners' => array(
                    'Turn 1-2' => 'Fast right-left combination',
                    'Turn 4' => 'Hairpin',
                    'Turn 5-6' => 'High-speed esses',
                    'Turn 9' => 'Tight hairpin',
                    'Turn 12-13' => 'High-speed complex',
                    'Turn 15' => 'Final corner, sweeping right-hander'
                ),
                'elevation_change' => 6,
                'description' => 'Tilke\'s breakthrough design featuring long straights and tight corners. Often produces unpredictable races due to tropical weather.',
                'history' => 'Hosted F1 from 1999-2017 and continues to host MotoGP. Famous for last-lap dramas and monsoon-affected races.',
                'coordinates' => array('lat' => 2.7603, 'lng' => 101.7383)
            ),
            
            'mount-panorama' => array(
                'name' => 'Mount Panorama Circuit',
                'country' => 'Australia',
                'region' => 'Australasia',
                'location' => 'Bathurst, New South Wales',
                'length_km' => 6.213,
                'length_miles' => 3.861,
                'turns' => 23,
                'direction' => 'Clockwise',
                'type' => 'Public Road/Semi-Permanent',
                'status' => 'Active',
                'opened' => 1938,
                'architect' => 'Various, developed over time',
                'fia_grade' => '3',
                'lap_record' => '2:01.567 (Shane van Gisbergen, 2023)',
                'capacity' => 45000,
                'events' => array('Bathurst 1000', 'Bathurst 12 Hour'),
                'famous_corners' => array(
                    'Hell Corner' => 'Turn 1, 180-degree right at base of mountain',
                    'Mountain Straight' => 'Long uphill climb',
                    'Griffins Bend' => 'Turn 2, left-hander on climb',
                    'The Cutting' => 'Fast section with walls close by',
                    'Sulman Park' => 'Turns 4-5',
                    'McPhillamy Park' => 'Summit of the mountain',
                    'Skyline' => 'Turn 8, at the highest point',
                    'The Esses' => 'Turns 9-10, downhill S-bends',
                    'The Dipper' => 'Turn 11, dramatic downhill plunge',
                    'Forrest Elbow' => 'Turn 12, tight left on descent',
                    'Conrod Straight' => 'High-speed 1.9km straight down the mountain',
                    'The Chase' => 'Fast sweeping complex, Turns 17-22',
                    'Murray Corner' => 'Final corner, Turn 23'
                ),
                'elevation_change' => 174,
                'description' => 'Australia\'s most iconic circuit, climbing Mount Panorama with massive elevation change. The Bathurst 1000 is the country\'s biggest motorsport event.',
                'history' => 'A public road turned race circuit, it has hosted the iconic Bathurst 1000 (formerly Bathurst 500) since 1963. The track\'s character and dangers are legendary in Australian motorsport.',
                'coordinates' => array('lat' => -33.4375, 'lng' => 149.5617)
            ),
            
            'phillip-island' => array(
                'name' => 'Phillip Island Grand Prix Circuit',
                'country' => 'Australia',
                'region' => 'Australasia',
                'location' => 'Phillip Island, Victoria',
                'length_km' => 4.448,
                'length_miles' => 2.764,
                'turns' => 12,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1956,
                'architect' => 'Various',
                'fia_grade' => '2',
                'lap_record' => '1:27.899 (Jorge Martin, 2023)',
                'capacity' => 50000,
                'events' => array('MotoGP Australian GP', 'World Superbike'),
                'famous_corners' => array(
                    'Doohan Corner' => 'Turn 1, named after Mick Doohan',
                    'Southern Loop' => 'Turns 2-3, tight hairpin complex',
                    'Honda Corner' => 'Turn 4, right-hander',
                    'Siberia' => 'Turn 5-6 complex',
                    'Lukey Heights' => 'Turn 7-8, high-speed left-right',
                    'MG Corner' => 'Turn 9, tight right hairpin',
                    'Gardner Straight' => 'Leading to Turn 10',
                    'Turn 10' => 'Fast right-hander',
                    'Stoner Corner' => 'Turn 11, named after Casey Stoner',
                    'Turn 12' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 28,
                'description' => 'A fast, flowing circuit set on a stunning coastal location with ocean views. Beloved by MotoGP riders for its high-speed nature.',
                'history' => 'Has hosted motorcycle racing since 1931 and car racing since 1956. The current layout dates from 1988 and is famous for close MotoGP racing.',
                'coordinates' => array('lat' => -38.5022, 'lng' => 145.2331)
            ),
            
            'yas-marina' => array(
                'name' => 'Yas Marina Circuit',
                'country' => 'United Arab Emirates',
                'region' => 'Middle East',
                'location' => 'Abu Dhabi, Yas Island',
                'length_km' => 5.281,
                'length_miles' => 3.281,
                'turns' => 16,
                'direction' => 'Counter-clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 2009,
                'architect' => 'Hermann Tilke',
                'fia_grade' => '1',
                'lap_record' => '1:26.103 (Max Verstappen, 2021)',
                'capacity' => 60000,
                'events' => array('Formula 1 Abu Dhabi GP', 'WEC 6 Hours'),
                'famous_corners' => array(
                    'Turn 1' => 'Tight left-hander',
                    'Turn 5' => 'Hairpin in hotel section',
                    'Turn 6-7' => 'Fast chicane under hotel',
                    'Turn 9' => 'Right-hander onto back straight',
                    'Turn 11-14' => 'Hotel section with tight corners (modified 2021)',
                    'Turn 16' => 'Final corner under grandstand'
                ),
                'elevation_change' => 11,
                'description' => 'A spectacular twilight race venue that transitions from daylight to night during the race. Features a section passing through the Yas Hotel.',
                'history' => 'Opened in 2009 as the season finale venue, it has been modified in 2021 to improve overtaking. Scene of the controversial 2021 championship decider.',
                'coordinates' => array('lat' => 24.4672, 'lng' => 54.6031)
            ),
            
            'jeddah' => array(
                'name' => 'Jeddah Corniche Circuit',
                'country' => 'Saudi Arabia',
                'region' => 'Middle East',
                'location' => 'Jeddah',
                'length_km' => 6.174,
                'length_miles' => 3.836,
                'turns' => 27,
                'direction' => 'Counter-clockwise',
                'type' => 'Street Circuit',
                'status' => 'Active',
                'opened' => 2021,
                'architect' => 'Tilke Engineers',
                'fia_grade' => '1',
                'lap_record' => '1:30.734 (Lewis Hamilton, 2021)',
                'capacity' => 45000,
                'events' => array('Formula 1 Saudi Arabian GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Tight left-hander',
                    'Turns 4-6' => 'Fast sweeping section',
                    'Turn 13' => 'Tight hairpin',
                    'Turns 22-24' => 'Fast kink section on back straight',
                    'Turn 27' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 12,
                'description' => 'The fastest street circuit in the world, with an average speed over 250 km/h. Features high-speed sections between concrete walls.',
                'history' => 'Opened in 2021 as part of Saudi Arabia\'s push into motorsport. Known for its extremely narrow margins for error at high speed.',
                'coordinates' => array('lat' => 21.6315, 'lng' => 39.1044)
            ),
            
            'daytona' => array(
                'name' => 'Daytona International Speedway',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Daytona Beach, Florida',
                'length_km' => 5.729,
                'length_miles' => 3.560,
                'turns' => 12,
                'direction' => 'Clockwise',
                'type' => 'Permanent Roval/Oval',
                'status' => 'Active',
                'opened' => 1959,
                'architect' => 'William France Sr.',
                'fia_grade' => '2',
                'lap_record' => '1:39.504 (Casey Mears, 2004)',
                'capacity' => 101500,
                'events' => array('Daytona 500', '24 Hours of Daytona', 'Rolex 24'),
                'famous_corners' => array(
                    'Turn 1 (Oval)' => '31-degree banked turn',
                    'Backstretch' => 'High-speed banking',
                    'Turn 3 (Oval)' => '31-degree banked turn',
                    'Tri-Oval' => 'Distinctive kinked front straight',
                    'International Horseshoe' => 'Infield section used for road course',
                    'Bus Stop Chicane' => 'Tight chicane in infield',
                    'NASCAR Turn 1' => 'First turn after start/finish on road course'
                ),
                'elevation_change' => 9,
                'description' => 'The "World Center of Racing" featuring a tri-oval with 31-degree banking and an infield road course. Home to the most prestigious events in American motorsport.',
                'history' => 'Built by Bill France Sr. to replace beach racing, it has hosted the Daytona 500 since 1959 and the 24 Hours of Daytona since 1966. Underwent major renovation in 2016.',
                'coordinates' => array('lat' => 29.1855, 'lng' => -81.0692)
            ),
            
            'indianapolis' => array(
                'name' => 'Indianapolis Motor Speedway',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Speedway, Indiana',
                'length_km' => 4.023,
                'length_miles' => 2.500,
                'turns' => 4,
                'direction' => 'Counter-clockwise',
                'type' => 'Permanent Oval/Road Course',
                'status' => 'Active',
                'opened' => 1909,
                'architect' => 'Carl G. Fisher',
                'fia_grade' => '1',
                'lap_record' => '37.895 seconds (Arie Luyendyk, 1996, qualifying)',
                'capacity' => 257325,
                'events' => array('Indianapolis 500', 'NASCAR Brickyard 400'),
                'famous_corners' => array(
                    'Turn 1' => '9-degree banked left turn',
                    'Turn 2' => '9-degree banked left turn',
                    'Backstretch' => 'Between turns 2 and 3',
                    'Turn 3' => '9-degree banked left turn',
                    'Turn 4' => '9-degree banked left turn',
                    'Yard of Bricks' => 'Start/finish line marked by original bricks'
                ),
                'elevation_change' => 0,
                'description' => 'The most famous racetrack in the world, known as "The Brickyard." The Indianapolis 500 is the largest single-day sporting event globally.',
                'history' => 'Opened in 1909, the track was originally paved with 3.2 million bricks. Hosted F1 from 2000-2007 using an infield road course. The Indy 500 has been held since 1911 (except during wars).',
                'coordinates' => array('lat' => 39.7956, 'lng' => -86.2353)
            ),
            
            'baku' => array(
                'name' => 'Baku City Circuit',
                'country' => 'Azerbaijan',
                'region' => 'Asia',
                'location' => 'Baku',
                'length_km' => 6.003,
                'length_miles' => 3.730,
                'turns' => 20,
                'direction' => 'Counter-clockwise',
                'type' => 'Street Circuit',
                'status' => 'Active',
                'opened' => 2016,
                'architect' => 'Tilke Engineers',
                'fia_grade' => '1',
                'lap_record' => '1:43.009 (Charles Leclerc, 2019)',
                'capacity' => 30000,
                'events' => array('Formula 1 Azerbaijan GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Tight left-hander',
                    'Turn 3' => 'Slow corner in old town',
                    'Turns 8-9' => 'Narrow section in old town',
                    'Castle Section' => 'Narrow 7.6m wide section past ancient walls',
                    'Turn 15' => 'Sharp left-hander',
                    'Turn 16' => 'Left-hander onto long straight',
                    'Turn 20' => 'Flat-out right at end of 2.2km straight'
                ),
                'elevation_change' => 21,
                'description' => 'Features the longest straight in F1 (over 2km) combined with a narrow old town section just 7.6m wide. A contrast circuit of extremes.',
                'history' => 'Opened in 2016 as part of Azerbaijan\'s investment in motorsport. Has produced many dramatic and unpredictable races with frequent safety cars.',
                'coordinates' => array('lat' => 40.3725, 'lng' => 49.8533)
            ),
            
            'shanghai' => array(
                'name' => 'Shanghai International Circuit',
                'country' => 'China',
                'region' => 'Asia',
                'location' => 'Shanghai',
                'length_km' => 5.451,
                'length_miles' => 3.387,
                'turns' => 16,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 2004,
                'architect' => 'Hermann Tilke',
                'fia_grade' => '1',
                'lap_record' => '1:32.238 (Michael Schumacher, 2004)',
                'capacity' => 200000,
                'events' => array('Formula 1 Chinese GP'),
                'famous_corners' => array(
                    'Turns 1-3' => 'Tightening spiral combination',
                    'Turn 6' => 'Hairpin',
                    'Turns 7-8' => 'Fast flowing section',
                    'Turn 11' => 'Hairpin',
                    'Turn 13' => 'High-speed left kink onto back straight',
                    'Turn 14-15' => 'Fast chicane'
                ),
                'elevation_change' => 8,
                'description' => 'The circuit layout resembles the Chinese character "shàng" (上) meaning "above." Features the unique tightening spiral of turns 1-3.',
                'history' => 'Opened in 2004 as part of China\'s push to host F1. Built on swampland requiring 40,000 concrete pillars to stabilize the ground.',
                'coordinates' => array('lat' => 31.3389, 'lng' => 121.2197)
            ),
            
            'singapore' => array(
                'name' => 'Marina Bay Street Circuit',
                'country' => 'Singapore',
                'region' => 'Asia',
                'location' => 'Marina Bay, Singapore',
                'length_km' => 4.940,
                'length_miles' => 3.070,
                'turns' => 19,
                'direction' => 'Counter-clockwise',
                'type' => 'Street Circuit',
                'status' => 'Active',
                'opened' => 2008,
                'architect' => 'KBR',
                'fia_grade' => '1',
                'lap_record' => '1:35.867 (Lewis Hamilton, 2023)',
                'capacity' => 90000,
                'events' => array('Formula 1 Singapore GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Sheares left-hander',
                    'Turn 5' => 'Tight left into Raffles Boulevard',
                    'Turn 7' => 'Tight hairpin',
                    'Turn 10' => 'Singapore Sling chicane (modified in 2023)',
                    'Turn 14' => 'Anderson Bridge corner',
                    'Turn 18' => 'Esplanade section',
                    'Turn 19' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 3,
                'description' => 'F1\'s first night race, held under spectacular floodlights. The hot, humid conditions and bumpy streets make it one of the most physically demanding races.',
                'history' => 'Debuted in 2008 as F1\'s first night race. Known for the "Crashgate" scandal in its first year. The track has been modified several times to improve racing.',
                'coordinates' => array('lat' => 1.2914, 'lng' => 103.8639)
            ),
            
            'barcelona' => array(
                'name' => 'Circuit de Barcelona-Catalunya',
                'country' => 'Spain',
                'region' => 'Europe',
                'location' => 'Montmeló, Barcelona',
                'length_km' => 4.657,
                'length_miles' => 2.894,
                'turns' => 16,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1991,
                'architect' => 'APPLUS IDIADA',
                'fia_grade' => '1',
                'lap_record' => '1:18.149 (Max Verstappen, 2021)',
                'capacity' => 140700,
                'events' => array('Formula 1 Spanish GP', 'MotoGP Catalan GP'),
                'famous_corners' => array(
                    'Turn 1 Elf' => 'First corner, right-hander',
                    'Turn 3 Renault' => 'Left-hander',
                    'Turn 5 Seat' => 'Fast chicane',
                    'Turn 7 Würth' => 'Slow right-hander',
                    'Turn 9 Campsa' => 'Fast right-hander',
                    'Turn 10 La Caixa' => 'Hairpin',
                    'Turn 12 Banc de Sabadell' => 'Fast right',
                    'Turn 14 New Holland' => 'Fast left',
                    'Turn 16 Europcar' => 'Final corner'
                ),
                'elevation_change' => 30,
                'description' => 'Primary F1 testing venue due to its variety of corner types. Located near Barcelona, it was revised in 2023 to create more overtaking opportunities.',
                'history' => 'Built for the 1992 Olympics, it has hosted F1 continuously since 1991. Known as a technical circuit where cars that work well here tend to work everywhere.',
                'coordinates' => array('lat' => 41.5700, 'lng' => 2.2611)
            ),
            
            'paul-ricard' => array(
                'name' => 'Circuit Paul Ricard',
                'country' => 'France',
                'region' => 'Europe',
                'location' => 'Le Castellet, Provence',
                'length_km' => 5.842,
                'length_miles' => 3.630,
                'turns' => 15,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1970,
                'architect' => 'Raymond Bernard',
                'fia_grade' => '1T',
                'lap_record' => '1:32.740 (Sebastian Vettel, 2019)',
                'capacity' => 90000,
                'events' => array('Formula 1 French GP (until 2022)', 'FIA WEC'),
                'famous_corners' => array(
                    'Turn 1 Virage du Pont' => 'First corner',
                    'Turn 3 Sainte Beaume' => 'Double left-hander',
                    'Turn 6' => 'Right-hander',
                    'Turn 8 Signes' => 'Fast right-hander',
                    'Turn 10 Virage de l\'École' => 'Chicane',
                    'Turn 11 Virage du Camp' => 'Left-hander onto Mistral Straight',
                    'Mistral Straight' => 'Long straight with chicane',
                    'Turn 15' => 'Final corner'
                ),
                'elevation_change' => 32,
                'description' => 'Famous for its blue and red run-off areas, the circuit is a world-class testing facility with 167 possible configurations.',
                'history' => 'Opened in 1970, financed by pastis magnate Paul Ricard. Known for hosting the dramatic 1986 F1 season finale. Returned to F1 in 2018 after extensive modernization.',
                'coordinates' => array('lat' => 43.2506, 'lng' => 5.7917)
            ),
            
            'mexico-city' => array(
                'name' => 'Autódromo Hermanos Rodríguez',
                'country' => 'Mexico',
                'region' => 'North America',
                'location' => 'Mexico City',
                'length_km' => 4.304,
                'length_miles' => 2.674,
                'turns' => 17,
                'direction' => 'Clockwise',
                'type' => 'Permanent Road Course',
                'status' => 'Active',
                'opened' => 1959,
                'architect' => 'Oscar Fernandez',
                'fia_grade' => '1',
                'lap_record' => '1:17.774 (Valtteri Bottas, 2021)',
                'capacity' => 120000,
                'events' => array('Formula 1 Mexico City GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Fast right-hander after start',
                    'Turn 4' => 'Hairpin',
                    'Turns 7-9' => 'Esses section',
                    'Turn 12 Horquilla' => 'Tight hairpin',
                    'Turn 13-14' => 'Fast S-bend',
                    'Foro Sol Stadium Section' => 'Final corners through baseball stadium, Turns 15-17'
                ),
                'elevation_change' => 9,
                'description' => 'Located at 2,285m altitude, the thin air reduces downforce by 20% and affects engine power. The stadium section creates an incredible atmosphere.',
                'history' => 'Named after racing brothers Ricardo and Pedro Rodriguez. Hosted F1 from 1963-1970 and 1986-1992, then returned in 2015 after major renovation including the stadium section.',
                'coordinates' => array('lat' => 19.4042, 'lng' => -99.0907)
            ),
            
            'las-vegas' => array(
                'name' => 'Las Vegas Strip Circuit',
                'country' => 'USA',
                'region' => 'North America',
                'location' => 'Las Vegas, Nevada',
                'length_km' => 6.201,
                'length_miles' => 3.853,
                'turns' => 17,
                'direction' => 'Counter-clockwise',
                'type' => 'Street Circuit',
                'status' => 'Active',
                'opened' => 2023,
                'architect' => 'Tilke Engineers',
                'fia_grade' => '1',
                'lap_record' => '1:35.490 (Oscar Piastri, 2023)',
                'capacity' => 105000,
                'events' => array('Formula 1 Las Vegas GP'),
                'famous_corners' => array(
                    'Turn 1' => 'Left-hander after long pit straight',
                    'Turn 5' => 'Hairpin',
                    'Turn 6' => 'Right-hander onto Las Vegas Boulevard',
                    'Turn 14' => 'Sweeping right in front of the Sphere',
                    'Strip Section' => 'High-speed section passing famous casinos',
                    'Turn 17' => 'Final corner onto pit straight'
                ),
                'elevation_change' => 5,
                'description' => 'A spectacular night race down the famous Las Vegas Strip, passing world-famous casinos and hotels. Run in cool desert night conditions.',
                'history' => 'Debuted in 2023 as F1\'s latest American venue. Different from the 1981-82 Caesar\'s Palace parking lot circuit. Aims to be the most glamorous race on the calendar.',
                'coordinates' => array('lat' => 36.1146, 'lng' => -115.1728)
            )
        );
    }
    
    /**
     * Search tracks by query
     */
    public static function search_tracks($query, $limit = 10) {
        $tracks = self::get_all_tracks();
        $results = array();
        $query = strtolower($query);
        
        foreach ($tracks as $id => $track) {
            $score = 0;
            
            // Check name match
            if (stripos($track['name'], $query) !== false) {
                $score += 100;
            }
            
            // Check country match
            if (stripos($track['country'], $query) !== false) {
                $score += 50;
            }
            
            // Check corner names
            if (!empty($track['famous_corners'])) {
                foreach ($track['famous_corners'] as $corner_name => $corner_desc) {
                    if (stripos($corner_name, $query) !== false || stripos($corner_desc, $query) !== false) {
                        $score += 30;
                        break;
                    }
                }
            }
            
            // Check description
            if (!empty($track['description']) && stripos($track['description'], $query) !== false) {
                $score += 20;
            }
            
            // Check events
            if (!empty($track['events'])) {
                foreach ($track['events'] as $event) {
                    if (stripos($event, $query) !== false) {
                        $score += 25;
                        break;
                    }
                }
            }
            
            if ($score > 0) {
                $results[$id] = array(
                    'track' => $track,
                    'score' => $score
                );
            }
        }
        
        // Sort by score
        uasort($results, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        return array_slice($results, 0, $limit);
    }
    
    /**
     * Get track by ID
     */
    public static function get_track($track_id) {
        $tracks = self::get_all_tracks();
        return isset($tracks[$track_id]) ? $tracks[$track_id] : null;
    }
    
    /**
     * Get tracks by region
     */
    public static function get_tracks_by_region($region) {
        $tracks = self::get_all_tracks();
        $results = array();
        
        foreach ($tracks as $id => $track) {
            if (strtolower($track['region']) === strtolower($region)) {
                $results[$id] = $track;
            }
        }
        
        return $results;
    }
    
    /**
     * Get tracks by country
     */
    public static function get_tracks_by_country($country) {
        $tracks = self::get_all_tracks();
        $results = array();
        
        foreach ($tracks as $id => $track) {
            if (strtolower($track['country']) === strtolower($country)) {
                $results[$id] = $track;
            }
        }
        
        return $results;
    }
    
    /**
     * Get all unique countries
     */
    public static function get_countries() {
        $tracks = self::get_all_tracks();
        $countries = array();
        
        foreach ($tracks as $track) {
            $countries[$track['country']] = $track['region'];
        }
        
        ksort($countries);
        return $countries;
    }
    
    /**
     * Get track statistics
     */
    public static function get_stats() {
        $tracks = self::get_all_tracks();
        $stats = array(
            'total_tracks' => count($tracks),
            'regions' => array(),
            'countries' => array(),
            'fia_grade_1' => 0,
            'street_circuits' => 0,
            'average_length' => 0,
            'longest_track' => null,
            'shortest_track' => null,
            'most_corners' => null
        );
        
        $total_length = 0;
        $longest_length = 0;
        $shortest_length = PHP_INT_MAX;
        $most_turns = 0;
        
        foreach ($tracks as $id => $track) {
            // Region count
            if (!isset($stats['regions'][$track['region']])) {
                $stats['regions'][$track['region']] = 0;
            }
            $stats['regions'][$track['region']]++;
            
            // Country count
            if (!isset($stats['countries'][$track['country']])) {
                $stats['countries'][$track['country']] = 0;
            }
            $stats['countries'][$track['country']]++;
            
            // FIA Grade 1
            if (!empty($track['fia_grade']) && $track['fia_grade'] === '1') {
                $stats['fia_grade_1']++;
            }
            
            // Street circuits
            if (strpos(strtolower($track['type']), 'street') !== false) {
                $stats['street_circuits']++;
            }
            
            // Length stats
            if (!empty($track['length_km'])) {
                $total_length += $track['length_km'];
                
                if ($track['length_km'] > $longest_length) {
                    $longest_length = $track['length_km'];
                    $stats['longest_track'] = $track['name'];
                }
                
                if ($track['length_km'] < $shortest_length) {
                    $shortest_length = $track['length_km'];
                    $stats['shortest_track'] = $track['name'];
                }
            }
            
            // Most corners
            if (!empty($track['turns']) && $track['turns'] > $most_turns) {
                $most_turns = $track['turns'];
                $stats['most_corners'] = $track['name'];
            }
        }
        
        $stats['average_length'] = round($total_length / count($tracks), 2);
        
        return $stats;
    }
    
    /**
     * Format track info for chatbot response
     */
    public static function format_track_info($track) {
        $info = "**" . $track['name'] . "** (" . $track['country'] . ")\n\n";
        
        $info .= "📍 **Location:** " . $track['location'] . "\n";
        $info .= "📏 **Length:** " . $track['length_km'] . " km (" . $track['length_miles'] . " miles)\n";
        $info .= "🔄 **Turns:** " . $track['turns'] . "\n";
        $info .= "➡️ **Direction:** " . $track['direction'] . "\n";
        $info .= "🏁 **Type:** " . $track['type'] . "\n";
        
        if (!empty($track['fia_grade'])) {
            $info .= "⭐ **FIA Grade:** " . $track['fia_grade'] . "\n";
        }
        
        if (!empty($track['lap_record'])) {
            $info .= "⏱️ **Lap Record:** " . $track['lap_record'] . "\n";
        }
        
        if (!empty($track['elevation_change'])) {
            $info .= "📈 **Elevation Change:** " . $track['elevation_change'] . "m\n";
        }
        
        if (!empty($track['opened'])) {
            $info .= "📅 **Opened:** " . $track['opened'] . "\n";
        }
        
        if (!empty($track['description'])) {
            $info .= "\n📝 **Description:**\n" . $track['description'] . "\n";
        }
        
        if (!empty($track['famous_corners']) && count($track['famous_corners']) > 0) {
            $info .= "\n🏎️ **Famous Corners:**\n";
            foreach ($track['famous_corners'] as $name => $desc) {
                $info .= "• **$name**: $desc\n";
            }
        }
        
        if (!empty($track['events'])) {
            $info .= "\n🏆 **Major Events:** " . implode(', ', $track['events']) . "\n";
        }
        
        return $info;
    }
}
