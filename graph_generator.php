<?php 

    require( "formatter.php" );
    
    function generate_object( $iri_base, $in ){
        return ( ($in && ($in != "''")) ? "<".$iri_base.$in.">" : null );
    }

    function local_objects( $in )       { return generate_object( "http://127.0.0.1/objects/" , $in ); };
    function local_terms( $in )         { return generate_object( "http://127.0.0.1/terms/" , $in ); };
    function local_groups( $in )        { return generate_object( "http://127.0.0.1/groups/" , $in ); };
    function foaf( $in )                { return generate_object( "http://xmlns.com/foaf/0.1/" , $in ); };
    function rdf( $in )                 { return generate_object( "http://www.w3.org/1999/02/22-rdf-syntax-ns#" , $in ); };
    function literal( $in )             { return ( ( $in && ( $in != "''" ) ) ? ( ( gettype( $in ) === "string" ) ? "'$in'^^xsd:string" : "'$in'^^xsd:integer" ) : null); };
    function generate_blank_node_id()   { return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 20); };

    function generate_triple( $subject, $predicate, $object ){
        return ( ($subject && $object) ? $subject." ".$predicate." ".$object."." : null );
    }

    $triples = "";

    function add_to_triples( $subject, $predicate, $object ){
        global $triples;
        $triple = generate_triple( $subject, $predicate, $object );
        ( $triple ? $triples.= $triple . "\n" : null );
        return $triple;
    }

    function add_to_triples_from_string( $triple ){
        global $triples;
        ( $triple ? $triples.= $triple . "\n" : null );
        return $triple;
    }

    function generate_list( $blank_node_id, $values ){
        $list_triples = [];
        array_push( $list_triples, generate_triple( $blank_node_id, rdf("type"), rdf( "list" ) ) );
        
        $values = array_filter( $values );
        $last_key = count($values) - 1;
        foreach( $values as $key => $value){
            array_push( $list_triples, generate_triple( $blank_node_id, rdf("first"), literal( $value ) ) );
            if( $key !== $last_key ){
                $next_blank_node_id = "_:b".generate_blank_node_id();
                array_push( $list_triples, generate_triple( $blank_node_id, rdf("rest"), $next_blank_node_id ) );
                $blank_node_id = $next_blank_node_id;
            }else{
                array_push( $list_triples, generate_triple( $blank_node_id, rdf("rest"), rdf("nil") ) );
            }
        }
        return $list_triples;
    }

    function process_list( $subject, $term, $list ){
        $blank_node_id = generate_blank_node_id();
        add_to_triples( $subject, local_terms($term), "_:b$blank_node_id" );
        $triples_list = generate_list( "_:b$blank_node_id", $list );
        array_map( "add_to_triples_from_string", $triples_list );
    }

    //formatter.php: $merged_articles
    foreach( $merged_articles as $key => $article ){

        $articleFname = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace( " ", "_", str_replace( ".", "", $article->title ) ) );
        $subject = local_objects( "article/" . $articleFname );

        //Block: Article
        {

            $title = add_to_triples( $subject, local_terms("title"), literal( $article->title ) );
            $year = add_to_triples( $subject, local_terms("year"), literal( $article->year ) );
            $source_title = add_to_triples( $subject, local_terms("source_title"), literal( $article->source_title ) );
            $volume = add_to_triples( $subject, local_terms("volume"), literal( $article->volume ));
            $issue = add_to_triples( $subject, local_terms("issue"), literal( $article->issue ));
            $article_no = add_to_triples( $subject, local_terms("article_no"), literal( $article->article_no ) );
            $page_start = add_to_triples( $subject, local_terms("page_start"), literal( $article->page_start ) );
            $page_end = add_to_triples( $subject, local_terms("page_end"), literal( $article->page_end ) );
            $cited_by = add_to_triples( $subject, local_terms("cited_by"), literal( $article->cited_by ) );
            $doi = add_to_triples( $subject, local_terms("doi"), literal( $article->doi ) );
            $link = add_to_triples( $subject, local_terms("link"), literal( $article->link ) );
            $abstract = add_to_triples( $subject, local_terms("abstract"), literal( $article->abstract ) );
            $correspondence_address = add_to_triples( $subject, local_terms("correspondence_address"), literal( $article->correspondence_address ) );
            $publisher = add_to_triples( $subject, local_terms("publisher"), literal( $article->publisher ) ); //_b:
            $issn = add_to_triples( $subject, local_terms("issn"), literal( $article->issn ) );
            $coden = add_to_triples( $subject, local_terms("coden"), literal( $article->coden ) );
            $pubmed_id = add_to_triples( $subject, local_terms("pubmed_id"), literal( $article->pubmed_id ) );
            $original_language = add_to_triples( $subject, local_terms("original_language"), literal( $article->original_language ) );
            $abbreviated_source_title = add_to_triples( $subject, local_terms("abbreviated_source_title"), literal( $article->abbreviated_source_title ) );
            $eid = add_to_triples( $subject, local_terms("eid"), literal( $article->eid ) );
            $ScopusArticle = add_to_triples( $subject, rdf("type"), local_objects( "ScopusArticle" ) );
           
            // process_list( ... ) calls add_to_triples( ... ) internaly.
            process_list( $subject, "author_keywords", $article->author_keywords );
            process_list( $subject, "index_keywords", $article->index_keywords );
            process_list( $subject, "chemicals_cas", $article->chemicals_cas );
            process_list( $subject, "tradenames", $article->tradenames );
            process_list( $subject, "references", $article->references );
            
        }
        
        //Block: author information
        {
            foreach( $article->authors as $key_ => $author ){

                $fname = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace( " ", "_", str_replace( ".", "", $author['name'] ) ) );

                $subject = local_objects( "author/" . $fname );
                $subject_name = str_replace( "'", "\\'", $author['name'] );
                $subject_id = $author['id'];
                $subject_affiliation = preg_replace("/[\n\r]/", "", str_replace( "'", "\\'", $author['affiliation'] ) );
                $object_affiliation = preg_replace("/[^a-zA-Z0-9]+/", "", $subject_affiliation);

                if( !is_numeric( $subject_id ) ){
                    $subject_id = null;
                }

                $author = add_to_triples( $subject, rdf("type"), foaf( "Person" ) );
                $written_by = add_to_triples( local_objects( "article/" . $articleFname ), local_terms("written_by"), $subject );

                $foaf_name = add_to_triples( $subject, foaf("name"), literal( $subject_name ) );
                $foaf_account = add_to_triples( $subject, foaf("account"), local_objects( $subject_id ) );
                $foaf_account_name = add_to_triples( local_objects( $subject_id ), foaf("accountName"), literal( $subject_id ) );
                $foaf_account_service_homepage = add_to_triples( local_objects( $subject_id ), foaf("accountServiceHomepage"), literal( "https://www.scopus.com/" ) );
                
                $foaf_group = add_to_triples( local_groups( $object_affiliation ), foaf("name"), literal( $subject_affiliation ) );
                $foaf_group_type = add_to_triples( local_groups( $object_affiliation ), rdf("type"), foaf( "group" ) );
                $foaf_member = add_to_triples( local_groups( $object_affiliation ), foaf("member"), $subject );

            }
        }
    }

    $file = 'triples.nt';
    file_put_contents( $file, $triples );
    
?>