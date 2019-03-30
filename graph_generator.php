<?php 
    require( "formatter.php" );

    function generate_object( $iri_base, $in ){
        return ( $in ? "<".$iri_base.$in.">" : null );
    }

    function local_objects( $in )   { return generate_object( "http://127.0.0.1/objects/" , $in ); };
    function local_terms( $in )     { return generate_object( "http://127.0.0.1/http://127.0.0.1/terms/" , $in ); };
    function local_groups( $in )    { return generate_object( "http://127.0.0.1/http://127.0.0.1/groups/" , $in ); };
    function foaf( $in )            { return generate_object( "http://127.0.0.1/http://xmlns.com/foaf/0.1/" , $in ); };
    function rdf( $in )             { return generate_object( "http://127.0.0.1/http://www.w3.org/1999/02/22-rdf-syntax-ns#" , $in ); };
    function literal( $in )         { return ( ( gettype( $in ) === "string" ) ? "'$in'" : $in ); };

    function generate_triple( $subject, $predicate, $object ){
        return ( ($subject && $object) ? $subject." ".$predicate." ".$object."." : null );
    }

    function generate_sparql_insert( $triple ){
        return ( ($triple) ? "SPARQL INSERT INTO <articles_metadata> {".$triple."}\n" : null );
    }

    $sparql_queries = "";
    $triples = "";

    function add_to_sparql_queries( $triple ){
        ( $triple ? $sparql_queries .= generate_sparql_insert( $triple ) : null );
    };

    function generate_list( $values ){
        $list = "(";
        foreach ($article->index_keywords as $key => $value) 
            $list .= literal( $value ) . " ";
        return $list . ")";
    }

    //formatter: $merged_articles
    foreach( $merged_articles as $key => $article ){

        $articleFname = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace( " ", "_",  str_replace( ".", "", $article->title ) ) );

        //Block: Article base
        {
            
            $subject = local_objects( "article/" . $articleFname );

            $title = generate_triple($subject, local_terms("title"), literal( $article->title ) );
            $year = generate_triple($subject, local_terms("year"), literal( $article->year ) );
            $source_title = generate_triple($subject, local_terms("source_title"), literal( $article->source_title ) );
            $volume = generate_triple($subject, local_terms("volume"), literal( $article->volume  ));
            $issue = generate_triple($subject, local_terms("issue"), literal( $article->issue  ));
            $article_no = generate_triple($subject, local_terms("article_no"), literal( $article->article_no  ) );
            $page_start = generate_triple($subject, local_terms("page_start"), literal( $article->page_start  ) );
            $page_end = generate_triple($subject, local_terms("page_end"), literal( $article->page_end  ) );
            $cited_by = generate_triple($subject, local_terms("cited_by"), literal( $article->cited_by  ) );
            $doi = generate_triple($subject, local_terms("doi"), literal( $article->doi  ) );
            $link = generate_triple($subject, local_terms("link"), literal( $article->link  ) );
            $abstract = generate_triple($subject, local_terms("abstract"), literal( $article->abstract  ) );
            $correspondence_address = generate_triple($subject, local_terms("correspondence_address"), literal( $article->correspondence_address  ) );
            $publisher = generate_triple($subject, local_terms("publisher"), literal( $article->publisher  ) ); //_b:
            $issn = generate_triple($subject, local_terms("issn"), literal( $article->issn  ) );
            $coden = generate_triple($subject, local_terms("coden"), literal( $article->coden  ) );
            $pubmed_id = generate_triple($subject, local_terms("pubmed_id"), literal( $article->pubmed_id  ) );
            $original_language = generate_triple($subject, local_terms("original_language"), literal( $article->original_language  ) );
            $abbreviated_source_title = generate_triple($subject, local_terms("abbreviated_source_title"), literal( $article->abbreviated_source_title  ) );
            $eid = generate_triple($subject, local_terms("eid"), literal( $article->eid  ) );
            $ScopusArticle = generate_triple($subject, rdf("type"), local_objects( "ScopusArticle"  ) );
            
            add_to_sparql_queries($title);
            add_to_sparql_queries($year);
            add_to_sparql_queries($source_title);
            add_to_sparql_queries($volume);
            add_to_sparql_queries($issue);
            add_to_sparql_queries($article_no);
            add_to_sparql_queries($page_start);
            add_to_sparql_queries($page_end);
            add_to_sparql_queries($cited_by);
            add_to_sparql_queries($doi);
            add_to_sparql_queries($link);
            add_to_sparql_queries($abstract);
            add_to_sparql_queries($correspondence_address);
            add_to_sparql_queries($publisher);
            add_to_sparql_queries($issn);
            add_to_sparql_queries($coden);
            add_to_sparql_queries($pubmed_id);
            add_to_sparql_queries($original_language);
            add_to_sparql_queries($abbreviated_source_title);
            add_to_sparql_queries($eid);
            add_to_sparql_queries($ScopusArticle);
            
        }

        //Block: author_keywords
        {
            $subject = local_objects( "article/" . $articleFname );
            $list = generate_triple($subject, local_terms("author_keywords"), generate_list( $article->author_keywords ) );
            add_to_sparql_queries($list);
        }

        //Block: index_keywords
        {
            $subject = local_objects( "article/" . $articleFname );
            $list = generate_triple($subject, local_terms("index_keywords"), generate_list( $article->index_keywords ) );
            add_to_sparql_queries($list);
        }

        //Block: chemicals_cas
        {
            $subject = local_objects( "article/" . $articleFname );
            $list = generate_triple($subject, local_terms("chemicals_cas"), generate_list( $article->chemicals_cas ) );
            add_to_sparql_queries($list);
        }

        //Block: tradenames
        {
            $subject = local_objects( "article/" . $articleFname );
            $list = generate_triple($subject, local_terms("tradenames"), generate_list( $article->tradenames ) );
            add_to_sparql_queries($list);
        }

        //Block: references
        {
            $subject = local_objects( "article/" . $articleFname );
            $list = generate_triple($subject, local_terms("references"), generate_list( $article->references ) );
            add_to_sparql_queries($list);
        }
        
        //Block: author information
        {
            foreach( $article->authors as $key_ => $author  ){

                $fname = preg_replace("/[^a-zA-Z0-9]+/", "", str_replace( " ", "_",  str_replace( ".", "", $author['name'] ) ) );

                $subject = local_objects( "author/" . $fname );
                $subject_name = str_replace( "'", "\\'", $author['name'] );
                $subject_id = $author['id'];
                $subject_affiliation = preg_replace("/[\n\r]/", "",  str_replace( "'", "\\'", $author['affiliation'] ) );
                $object_affiliation = preg_replace("/[^a-zA-Z0-9]+/", "", $subject_affiliation);

                if( !is_numeric( $subject_id ) ){
                    $subject_id = null;
                }

                $type_person = generate_triple($subject, rdf("type"), foaf( "Person"  ) );
                $written_by = generate_triple(local_objects( "article/" . $articleFname ), local_terms("written_by"), $subject );
                $foaf_name = generate_triple($subject, foaf("name"), literal( $author['name'] ) );
                
                $foaf_account = null;
                if( $subject_id ){
                    $foaf_account = 
                        $sparql_base .$subject . " " . 
                        "<" . $foaf . "account" . ">" . " " . 
                        "<" . $local_objects . $subject_id . ">" . ".};\n";
                }
                
                $foaf_account_name = null;
                if( $subject_id ){
                    $foaf_account_name = 
                        $sparql_base ."<" . $local_objects . $author['id'] . ">" . " " . 
                        "<" . $foaf . "accountName" . ">" . " " . 
                        $subject_id . ".};\n";
                }
                
                $foaf_account_service_homepage = null;
                if( $subject_id ){
                    $foaf_account_service_homepage = 
                        $sparql_base ."<" . $local_objects . $subject_id . ">" . " " . 
                        "<" . $foaf . "accountServiceHomepage" . ">" . " " . 
                        "'https://www.scopus.com/'" . ".};\n";
                }

                $foaf_group = 
                    $sparql_base ."<" . $local_groups . $object_affiliation . ">" . " " . 
                    "<" . $foaf . "name" . ">" . " " . 
                    literal( $subject_affiliation  ) . ".};\n";
                
                $foaf_group_type = 
                    $sparql_base ."<" . $local_groups . $object_affiliation . ">" . " " . 
                    $rdf_type . " " . 
                    "<" . $foaf . "group" . ">" . ".};\n";

                $foaf_member = 
                    $sparql_base ."<" . $local_groups . $object_affiliation . ">" . " " . 
                    "<" . $foaf . "member" . ">" . " " . 
                    $subject . ".};\n";

                $sparql_queries .=  $type_person  . 
                $written_by .
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

    $file = 'graph.rq';
    file_put_contents( $file, $sparql_queries );


?>