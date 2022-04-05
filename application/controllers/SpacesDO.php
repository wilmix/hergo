<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
class SpacesDO extends CI_Controller {
  public function index()
  {
    // Configure a client using Spaces
    $client = new Aws\S3\S3Client($this->config->item('credentialsSpacesDO'));
    // Listing all Spaces in the region
    /*$spaces = $client->listBuckets();
    foreach ($spaces['Buckets'] as $space){
        echo $space['Name']."\n";
    }*/




/*     $objects = $client->listObjects([
      'Bucket' => 'hergo-space'
    ]);

      //print_r($objects['Contents']);
      foreach ($objects['Contents'] as $object){
        echo $object['Key']."</br>";
      }
 */




    // Upload a file to the Space
    $insert = $client->putObject([
      'Bucket' => 'hergo-space',
      'Key'    => 'articulos/file050422.txt',
      'Body'   => 'el body.',
      'ACL' => 'public-read'
    ]);
    print_r($insert);

  }
}