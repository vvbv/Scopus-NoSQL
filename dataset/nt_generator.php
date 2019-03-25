<?php 
    require( "formatter.php" );

    $local_objects = "http://127.0.0.1/objects/";
    $local_terms = "http://127.0.0.1/terms/";
    $local_groups = "http://127.0.0.1/groups/";
    $foaf = "http://xmlns.com/foaf/0.1/";
    $rdf = "http://www.w3.org/1999/02/22-rdf-syntax-ns#";
    $wikidata = "http://www.wikidata.org/entity/";

    $rdf_type = "<" . $rdf . "type" . ">";

    $base = "SPARQL INSERT INTO <articles_metadata> {";

    //formatter: $merged_articles
    foreach( $merged_articles as $key => $article ){

        //Block: Article base
        {
            $fname = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace( " ", "_",  str_replace( ".", "", $article->title ) ) );
            $subject = "<" . $local_objects . "article/" . $fname . ">";

            $title =
                $base . $subject . " " . 
                "<" . $local_terms . "title" . ">" . " " . 
                "\"" . $article->title . "\"" . ".};\n";
            
            

        }
        
        //Block: author information
        {
            foreach( $article->authors as $key_ => $author  ){

                $fname = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace( " ", "_",  str_replace( ".", "", $author['name'] ) ) );

                $subject = "<" . $local_objects . "author/" . $fname . ">";
                $subject_name = str_replace( "\"", "\\\"", $author['name'] );
                $subject_id = $author['id'];
                $subject_affiliation = str_replace( "\"", "\\\"", $author['affiliation'] );
                $object_affiliation = preg_replace("/[^a-zA-Z0-9]+/", "", $subject_affiliation);

                $type_person = 
                    $base . $subject . " " . 
                    $rdf_type . " " . 
                    "<" .  $foaf . "Person" . ">" . ".};\n"; 
                    
                $foaf_name = 
                    $base .$subject . " " . 
                    "<" . $foaf . "name" . ">" . " " . 
                    "\"" .  $author['name'] . "\"" . ".};\n";
                
                $foaf_account = 
                    $base .$subject . " " . 
                    "<" . $foaf . "account" . ">" . " " . 
                    "<" . $local_objects . $author['id'] . ">" . ".};\n";
                
                $foaf_account_name = 
                    $base ."<" . $local_objects . $author['id'] . ">" . " " . 
                    "<" . $foaf . "accountName" . ">" . " " . 
                    $subject_id . ".};\n";
                
                $foaf_account_service_homepage = 
                    $base ."<" . $local_objects . $author['id'] . ">" . " " . 
                    "<" . $foaf . "accountServiceHomepage" . ">" . " " . 
                    "\"https://www.scopus.com/\"" . ".};\n";

                $foaf_group = 
                    $base ."<" . $local_groups . $object_affiliation . ">" . " " . 
                    "<" . $foaf . "name" . ">" . " " . 
                    "\"" . $subject_affiliation . "\"" . ".};\n";
                
                $foaf_group_type = 
                    $base ."<" . $local_groups . $object_affiliation . ">" . " " . 
                    $rdf_type . " " . 
                    "<" . $foaf . "group" . ">" . ".};\n";

                $foaf_member = 
                    $base ."<" . $local_groups . $object_affiliation . ">" . " " . 
                    "<" . $foaf . "member" . ">" . " " . 
                    $subject . ".};\n";

                echo $type_person  . 
                $foaf_name  . 
                $foaf_account  . 
                $foaf_account_name  . 
                $foaf_account_service_homepage . 
                $foaf_group  . 
                $foaf_group_type  . 
                $foaf_member;

            }
        }

    }


?>