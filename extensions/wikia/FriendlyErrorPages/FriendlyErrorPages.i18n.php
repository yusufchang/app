<?php
/**
 * Internationalisation for myextension
 *
 * @file
 * @ingroup Extensions
 */
$messages = array();
 
 /** English
 * @author <adam.karminski@wikia-inc.com>
 */

$messages[ 'en' ] = array(
        'friendlyerrorpages' => "Friendly Error Pages", // Important! This is the string that appears on Special:SpecialPages
        'friendlyerrorpages-desc' => "Extension that provides user friendly error pages for 4xx and 5xx HTTP statuses.",
        'friendlyerrorpages-specialpage-click' => "Click on a link to trigger a desired HTTP status and display an error page.",

        'friendlyerrorpages-errorpage-header-400' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-400'
                => 'Sorry, it seems your browser is not responding properly.
Try refreshing this page. If problem persists try Googling for possible solutions: Error 400 bad request.
You may go to [http://wikia.com our homepage]',
        'friendlyerrorpages-errorpage-technical-explanation-400' => "The request could not be understood by the server due to malformed syntax. The client SHOULD NOT repeat the request without modifications.",
        
        'friendlyerrorpages-errorpage-header-403' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-403'
                => 'Sorry, this page is off limits to unauthorized users.
You are seeing this error as you are trying to access a page that cannot be viewed.

You can:
* Double check the web address for typos
* Head back to [http://wikia.com our homepage]',
        'friendlyerrorpages-errorpage-technical-explanation-403' => "The server understood the request, but is refusing to fulfill it.",
        
        'friendlyerrorpages-errorpage-header-405' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-405' => "The request you made is not one that we support. Please check the URL and try again. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-405' => "The method specified in the Request-Line is not allowed for the resource identified by the Request-URI. The response MUST include an Allow header containing a list of valid methods for the requested resource.",
        
        'friendlyerrorpages-errorpage-header-406' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-406' => "The request you made is not one that we support. Please check the URL and try again. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-406' => "The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request.",
        
        'friendlyerrorpages-errorpage-header-408' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-408' => "Unfortunately our servers took too long for your request to complete. Sorry and please try again. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-408' => "The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time.",
        
        'friendlyerrorpages-errorpage-header-409' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-409' => "There appears to be a conflict in the request you made. Please check the URL and try again. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-409' => "The request could not be completed due to a conflict with the current state of the resource. This code is only allowed in situations where it is expected that the user might be able to resolve the conflict and resubmit the request.",
        
        'friendlyerrorpages-errorpage-header-411' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-411' => "Something went wrong during the upload. Please try again. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-411' => "The server refuses to accept the request without a defined Content- Length. The client MAY repeat the request if it adds a valid Content-Length header field containing the length of the message-body in the request message.",
        
        'friendlyerrorpages-errorpage-header-412' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-412' => "Your request does not meet all conditions of our servers. Please try again. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-412' => "The precondition given in one or more of the request-header fields evaluated to false when it was tested on the server. This response code allows the client to place preconditions on the current resource metainformation (header field data) and thus prevent the requested method from being applied to a resource other than the one intended.",
        
        'friendlyerrorpages-errorpage-header-413' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-413' => "The response to your request is too large. Please try again!",
        'friendlyerrorpages-errorpage-technical-explanation-413' => "The server is refusing to process a request because the request entity is larger than the server is willing or able to process. The server MAY close the connection to prevent the client from continuing the request.",
        
        'friendlyerrorpages-errorpage-header-414' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-414' => "The address you gave us is too long. Please check it for mistakes.",
        'friendlyerrorpages-errorpage-technical-explanation-414' => "The server is refusing to service the request because the Request-URI is longer than the server is willing to interpret. This rare condition is only likely to occur when a client has improperly converted a POST request to a GET request with long query information, when the client has descended into a URI black hole of redirection (e.g., a redirected URI prefix that points to a suffix of itself), or when the server is under attack by a client attempting to exploit security holes present in some servers using fixed-length buffers for reading or manipulating the Request-URI.",
        
        'friendlyerrorpages-errorpage-header-415' => "Oops, there appears to be a problem!",
        'friendlyerrorpages-errorpage-explanation-415' => "Our servers do not support this media type.",
        'friendlyerrorpages-errorpage-technical-explanation-415' => "Our servers do not support this media type, please adjust the form and re-submit. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        
        'friendlyerrorpages-errorpage-header-500' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-500' => "This is so embarrassing but there seems to be a small problem with our servers. We expect to be back up and running shortly. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-500' => "The server encountered an unexpected condition which prevented it from fulfilling the request.",
        
        'friendlyerrorpages-errorpage-header-501' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-501' => 'Sorry, there seems to be a problem with your Internet connection.The problem is usually caused by a third party product interfering with your browser.',
  'friendlyerrorpages-errorpage-technical-explanation-501' => "The server does not support the functionality required to fulfill the request. This is the appropriate response when the server does not recognize the request method and is not capable of supporting it for any resource.",
        
        'friendlyerrorpages-errorpage-header-502' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-502'
                => "It appears there is a problem with your IP connection and Wikia. Try clearing your browser cache and reloading the page. 
If you also see this problem on other websites then either your ISP has a major equipment failure/overload or there is something wrong with your internal Internet connection. We recommend reviewing our internet setup and contacting your ISP.",
        'friendlyerrorpages-errorpage-technical-explanation-502' => "The server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request.",
        
        'friendlyerrorpages-errorpage-header-503' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-503' => "This is so embarrassing but there seems to be a small problem with our servers or we are carrying out repairs. We expect to be back up and running shortly. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-503' => "The server is currently unable to handle the request due to a temporary overloading or maintenance of the server. The implication is that this is a temporary condition which will be alleviated after some delay.",
        
        'friendlyerrorpages-errorpage-header-504' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-504' => "This is so embarrassing but there seems to be a small problem with our servers or we are carrying out repairs. We expect to be back up and running shortly. If the problem continues, please feel free to [http://community.wikia.com/wiki/Special:Contact contact us].",
        'friendlyerrorpages-errorpage-technical-explanation-504' => "The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server specified by the URI (e.g. HTTP, FTP, LDAP) or some other auxiliary server (e.g. DNS) it needed to access in attempting to complete the request.",
        
        'friendlyerrorpages-errorpage-header-505' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-505' => "Interesting. You seem to be using a version of HTTP we don't support. Please use HTTP 1.1. If this problem persists please [http://community.wikia.com/wiki/Special:Contact contact us].",
);

/** Message documentation
 * @author <adam.karminski@wikia-inc.com>
 */
$messages[ 'qqq' ] = array(
        'friendlyerrorpages' => "Friendly Error Pages",
        'friendlyerrorpages-desc' => "{{desc}}",
);
