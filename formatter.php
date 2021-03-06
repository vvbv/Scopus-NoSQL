<?php

    $merged_articles = [];
    $mongo_dataset = "";

    $location = "json/";
    $fi = new FilesystemIterator($location, FilesystemIterator::SKIP_DOTS);
    $fileCount = iterator_count($fi);

    for ($i=1; $i < ( $fileCount + 1 ); $i++) { 
        $fp = fopen("json/$i.json", "r");
        $input = "";
        while (!feof($fp)){
            $input .= fgets($fp);
        }
        fclose($fp);
        
        $input = str_replace('"Authors":','"authors":',$input);
        $input = str_replace('"Author(s) ID":','"authors_id":',$input);
        $input = str_replace('"Title":','"title":',$input);
        $input = str_replace('"Year":','"year":',$input);
        $input = str_replace('"Source title":','"source_title":',$input);
        $input = str_replace('"Volume":','"volume":',$input);
        $input = str_replace('"Issue":','"issue":',$input);
        $input = str_replace('"Art. No.":','"article_no":',$input);
        $input = str_replace('"Page start":','"page_start":',$input);
        $input = str_replace('"Page end":','"page_end":',$input);
        $input = str_replace('"Page count":','"page_count":',$input);
        $input = str_replace('"Cited by":','"cited_by":',$input);
        $input = str_replace('"DOI":','"doi":',$input);
        $input = str_replace('"Link":','"link":',$input);
        $input = str_replace('"Affiliations":','"affiliations":',$input);
        $input = str_replace('"Authors with affiliations":','"authors_with_affiliations":',$input);
        $input = str_replace('"Abstract":','"abstract":',$input);
        $input = str_replace('"Author Keywords":','"author_keywords":',$input);
        $input = str_replace('"Index Keywords":','"index_keywords":',$input);
        $input = str_replace('"Molecular Sequence Numbers":','"molecular_sequence_numbers":',$input);
        $input = str_replace('"Chemicals/CAS":','"chemicals_cas":',$input);
        $input = str_replace('"Tradenames":','"tradenames":',$input);
        $input = str_replace('"Manufacturers":','"manufacturers":',$input);
        $input = str_replace('"Funding Details":','"funding_details":',$input);
        $input = str_replace('"Funding Details":','"funding_details":',$input);
        $input = str_replace('"Funding Text 1":','"funding_text_1":',$input);
        $input = str_replace('"Funding Text 2":','"funding_text_2":',$input);
        $input = str_replace('"Funding Text 3":','"funding_text_3":',$input);
        $input = str_replace('"References":','"references":',$input);
        $input = str_replace('"Correspondence Address":','"correspondence_address":',$input);
        $input = str_replace('"Editors":','"editors":',$input);
        $input = str_replace('"Sponsors":','"sponsors":',$input);
        $input = str_replace('"Publisher":','"publisher":',$input);
        $input = str_replace('"Conference name":','"conference_name":',$input);
        $input = str_replace('"Conference date":','"conference_date":',$input);
        $input = str_replace('"Conference location":','"conference_location":',$input);
        $input = str_replace('"Conference code":','"conference_code":',$input);
        $input = str_replace('"ISSN":','"issn":',$input);
        $input = str_replace('"ISBN":','"isbn":',$input);
        $input = str_replace('"CODEN":','"coden":',$input);
        $input = str_replace('"PubMed ID":','"pubmed_id":',$input);
        $input = str_replace('"Language of Original Document":','"original_language":',$input);
        $input = str_replace('"Abbreviated Source Title":','"abbreviated_source_title":',$input);
        $input = str_replace('"Document Type":','"document_type":',$input);
        $input = str_replace('"Publication Stage":','"publication_stage":',$input);
        $input = str_replace('"Access Type":','"access_type":',$input);
        $input = str_replace('"Source":','"source":',$input);
        $input = str_replace('"EID":','"eid":',$input);
        

        $arr_articles = json_decode( $input );
        foreach( $arr_articles as $key => $article ){

            $article->author_keywords =  array_map( function($in){return trim( addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $in ) ) ) ) );} ,explode (";", $article->author_keywords) );
            $article->index_keywords =  array_map( function($in){return trim( addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $in ) ) ) ) );} ,explode (";", $article->index_keywords) );
            $article->references =  array_map( function($in){return trim( addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $in ) ) ) ) );} ,explode (";", $article->references) );
            $article->chemicals_cas =  array_map( function($in){return trim( addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $in ) ) ) ) ) ;} ,explode (",", $article->chemicals_cas) );
            $article->tradenames =  array_map( function($in){return trim( addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $in ) ) ) ) );} ,explode (",", $article->tradenames) );
            $article->abstract = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->abstract ) ) ) );
            $article->title = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->title ) ) ) );
            $article->source_title = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->source_title ) ) ) );
            $article->article_no = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->article_no ) ) ) );
            $article->doi = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->doi ) ) ) );
            $article->link = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->link ) ) ) );
            $article->correspondence_address = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->correspondence_address ) ) ) );
            $article->publisher = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->publisher ) ) ) );
            $article->coden = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->coden ) ) ) );
            $article->abbreviated_source_title = addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $article->abbreviated_source_title ) ) ) );


            $authors = explode (",", $article->authors);  
            $authors_id = explode (";", $article->authors_id);
            $affiliations = explode (",", $article->affiliations);

            $authors_with_id = [];
            foreach( $authors as $key => $author ){
                $id = null;
                $affiliation = null;
                if( key_exists( $key, $authors_id ) ){
                    $id = $authors_id[ $key ];
                }
                if( key_exists( $key, $affiliations ) ){
                    $affiliation = $affiliations[ $key ];
                }
                $merged_author = [
                    'name' => trim( addslashes( utf8_encode(  str_replace( "'", "", str_replace( "$", "DOLLAR_SYM", $author ) ) ) ) ),
                    'id' => trim($id),
                    'affiliation' => trim($affiliation)
                ];
                array_push( $authors_with_id, $merged_author );
            }
            unset($article->authors_id);
            unset($article->affiliations);
            unset($article->authors_with_affiliations);
            $article->authors = $authors_with_id;
            
            //unused items removed
            unset($article->manufacturers);
            unset($article->funding_details);
            unset($article->funding_text_1);
            unset($article->funding_text_2);
            unset($article->funding_text_3);
            unset($article->editors);
            unset($article->sponsors);
            unset($article->conference_name);
            unset($article->conference_date);
            unset($article->conference_location);
            unset($article->conference_code);
            unset($article->isbn);
            unset($article->access_type);
            unset($article->page_count);
            unset($article->molecular_sequence_numbers);
            unset($article->document_type);
            unset($article->publication_stage);
            unset($article->source);

            $mongo_dataset .= json_encode( $article ) . "\n" ;
            array_push( $merged_articles, $article );
        }
        
    }

    $file = 'mongo_dataset.json';
    file_put_contents( $file, $mongo_dataset );
    $file = 'merged_articles.json';
    file_put_contents( $file, json_encode( $merged_articles ) );
    
?>