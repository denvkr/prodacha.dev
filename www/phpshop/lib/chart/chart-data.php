<?php

include_once( 'open-flash-chart.php' );

// generate some random data
srand((double)microtime()*1000000);

//
// We are diplaying 3 bar charts, so create 3
// bar chart objects:
//

$bar_1 = new bar( 50, '#0066CC' );
$bar_1->key( 'Me', 10 );

$bar_2 = new bar( 50, '#9933CC' );
$bar_2->key( 'You', 10 );

$bar_3 = new bar( 50, '#639F45' );
$bar_3->key( 'Them', 10 );

//
// NOTE: how we are filling 3 arrays full of data,
//       one for each bar on the graph
//
for( $i=0; $i<9; $i++ )
{
  $bar_1->data[] = rand(2,5);
  $bar_2->data[] = rand(5,9);
  $bar_3->data[] = rand(2,9);
}

// create the chart:
$g = new graph();
$g->title( 'Bar Chart', '{font-size: 26px;}' );

// add the 3 bar charts to it:
$g->data_sets[] = $bar_1;
$g->data_sets[] = $bar_2;
$g->data_sets[] = $bar_3;


//
$g->set_x_labels( array( 'January','February','March','April','May','June','July','August','September' ) );
// set the X axis to show every 2nd label:
$g->set_x_label_style( 10, '#9933CC', 0, 2 );
// and tick every second value:
$g->set_x_axis_steps( 2 );
//

$g->set_y_max( 10 );
$g->y_label_steps( 2 );
$g->set_y_legend( 'Open Flash Chart', 12, '0x736AFF' );
echo $g->render();
?>