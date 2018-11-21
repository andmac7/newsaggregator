<?php
    function parseXmlFeed($searchWords)
    {
        // Urls to comb for news
        $feedUrls = array("http://rss.cnn.com/rss/edition_world.rss","http://rss.cnn.com/rss/edition_world.rss", "http://feeds.bbci.co.uk/news/rss.xml","https://www.theguardian.com/world/rss","http://rss.nytimes.com/services/xml/rss/nyt/World.xml");
        $feed = array();
        foreach($feedUrls as $url)
        {
            // Parse rss feed
            $rss = new DOMDocument();
            $rss->load($url);

            foreach ($rss->getElementsByTagName('item') as $node) 
            {
                // Check if entry contains date
                if (isset($node->getElementsByTagName('pubDate')->item(0)->nodeValue)) {
                    $item = array ( 
                        'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                        'desc' => strip_tags($node->getElementsByTagName('description')->item(0)->nodeValue),
                        'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                        'date' => date("d-m-Y", strtotime($node->getElementsByTagName('pubDate')->item(0)->nodeValue)),
                        'url'  => $url
                    );
                    // Check for all words in news feeds
                    foreach ($searchWords as $searchWord)
                    {
                        // Strict substring matching
                        if (preg_match("~\b".$searchWord."\b~i", $item["title"]) || preg_match("~\b".$searchWord."\b~i", $item["desc"]))
                        {
                            $item += ['word' => $searchWord];
                            array_push($feed, $item);
                        }
                    }
                }
            }
        }
        return $feed;
    }
?>