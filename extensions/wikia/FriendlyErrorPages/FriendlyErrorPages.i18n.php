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

        'friendlyerrorpages-errorpage-header-400' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-400'
                => 'Sorry, it seems your browser is not responding properly. <br/>
Try refresing this page. If problem persists search Google for possible solutions. The error code you need is: Error 400 bad request <br/>
You may go to [http://wikia.com our homepage]',
        'friendlyerrorpages-errorpage-technical-explanation-400' => "The request could not be understood by the server due to malformed syntax. The client SHOULD NOT repeat the request without modifications.",
        
        'friendlyerrorpages-errorpage-header-403' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-403'
                => 'Sorry, this directory is off limits to unauthorized users. <br/>
You are seeing this page as your trying to access a directory that cannot be viewed. <br/>
<br/>
You can:
* Double check the web address for typos
* Head back to [http://wikia.com our homepage]',
        'friendlyerrorpages-errorpage-technical-explanation-403' => "The server understood the request, but is refusing to fulfill it.",
        
        'friendlyerrorpages-errorpage-header-405' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-405' => "We cannot do what you want us to do. Please check the URL for mistakes.",
        'friendlyerrorpages-errorpage-technical-explanation-405' => "The method specified in the Request-Line is not allowed for the resource identified by the Request-URI. The response MUST include an Allow header containing a list of valid methods for the requested resource.",
        
        'friendlyerrorpages-errorpage-header-406' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-406' => "We cannot do what you want us to do. Please check the URL for mistakes.",
        'friendlyerrorpages-errorpage-technical-explanation-406' => "The resource identified by the request is only capable of generating response entities which have content characteristics not acceptable according to the accept headers sent in the request.",
        
        'friendlyerrorpages-errorpage-header-408' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-408' => "Our servers waited too long for your request. Please try again!",
        'friendlyerrorpages-errorpage-technical-explanation-408' => "The client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time.",
        
        'friendlyerrorpages-errorpage-header-409' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-409' => "There is some kind of conflict. Please check the URL for mistakes.",
        'friendlyerrorpages-errorpage-technical-explanation-409' => "The request could not be completed due to a conflict with the current state of the resource. This code is only allowed in situations where it is expected that the user might be able to resolve the conflict and resubmit the request.",
        
        'friendlyerrorpages-errorpage-header-411' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-411' => "Our servers needs Content-Length (whatever) to fulfill your request. Please try again!",
        'friendlyerrorpages-errorpage-technical-explanation-411' => "The server refuses to accept the request without a defined Content- Length. The client MAY repeat the request if it adds a valid Content-Length header field containing the length of the message-body in the request message.",
        
        'friendlyerrorpages-errorpage-header-412' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-412' => "Your request does not meet all conditions of our servers. Please try again!",
        'friendlyerrorpages-errorpage-technical-explanation-412' => "The precondition given in one or more of the request-header fields evaluated to false when it was tested on the server. This response code allows the client to place preconditions on the current resource metainformation (header field data) and thus prevent the requested method from being applied to a resource other than the one intended.",
        
        'friendlyerrorpages-errorpage-header-413' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-413' => "The response to your request is too large. Please try again!",
        'friendlyerrorpages-errorpage-technical-explanation-413' => "The server is refusing to process a request because the request entity is larger than the server is willing or able to process. The server MAY close the connection to prevent the client from continuing the request.",
        
        'friendlyerrorpages-errorpage-header-414' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-414' => "The URL is too long. Please check it for mistakes.",
        'friendlyerrorpages-errorpage-technical-explanation-414' => "The server is refusing to service the request because the Request-URI is longer than the server is willing to interpret. This rare condition is only likely to occur when a client has improperly converted a POST request to a GET request with long query information, when the client has descended into a URI black hole of redirection (e.g., a redirected URI prefix that points to a suffix of itself), or when the server is under attack by a client attempting to exploit security holes present in some servers using fixed-length buffers for reading or manipulating the Request-URI.",
        
        'friendlyerrorpages-errorpage-header-415' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-415' => "Our servers do not support this media type.",
        'friendlyerrorpages-errorpage-technical-explanation-415' => "The server is refusing to service the request because the entity of the request is in a format not supported by the requested resource for the requested method.",
        
        'friendlyerrorpages-errorpage-header-500' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-500' => "This is so embarrassing but there seems to be a small problem with our servers. We expect to be back up and running shortly. <br/> <br/> It is best to be safe than sorry so we have notified the administrator to check it out.",
        'friendlyerrorpages-errorpage-technical-explanation-500' => "The server encountered an unexpected condition which prevented it from fulfilling the request.",
        
        'friendlyerrorpages-errorpage-header-501' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-501' => 'Sorry, there seems to be a problem with your internet. <br/>
The problem is usually caused by a third party product interfering with your browser. <br/>
<br/>
More information on this error can be found at: <br/> [http://support.microsoft.com/kb/811262 HTTP Error 501 - Microsoft Support]',
        'friendlyerrorpages-errorpage-technical-explanation-501' => "The server does not support the functionality required to fulfill the request. This is the appropriate response when the server does not recognize the request method and is not capable of supporting it for any resource.",
        
        'friendlyerrorpages-errorpage-header-502' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-502'
                => "This problem is due to poor IP communication between back-end computers, possibly including the Web server at the site you are trying to visit. Before analysing this problem, you should clear your browser cache completely. <br/>
If you are surfing the Web and see this problem for all Web sites you try to visit, then either:
* your ISP has a major equipment failure/overload
* there is something wrong with your internal Internet connection e.g. your firewall is not functioning correctly.
In the first case, only your ISP can help you. In the second case, you need to fix whatever it is that is preventing you reaching the Internet.<br/>",
        'friendlyerrorpages-errorpage-technical-explanation-502' => "The server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request.",
        
        'friendlyerrorpages-errorpage-header-503' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-503' => "We are temporarily down probably for repair or reboot. Please try again in a moment!",
        'friendlyerrorpages-errorpage-technical-explanation-503' => "The server is currently unable to handle the request due to a temporary overloading or maintenance of the server. The implication is that this is a temporary condition which will be alleviated after some delay.",
        
        'friendlyerrorpages-errorpage-header-504' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-504' => "There is something wrong with our upstream server. Please visit us later!",
        'friendlyerrorpages-errorpage-technical-explanation-504' => "The server, while acting as a gateway or proxy, did not receive a timely response from the upstream server specified by the URI (e.g. HTTP, FTP, LDAP) or some other auxiliary server (e.g. DNS) it needed to access in attempting to complete the request.",
        
        'friendlyerrorpages-errorpage-header-505' => "We are sorry!",
        'friendlyerrorpages-errorpage-explanation-505' => "We do not support this HTTP version. It is interesting. Please use HTTP 1.1.",
        'friendlyerrorpages-errorpage-technical-explanation-505' => "The server does not support, or refuses to support, the HTTP protocol version that was used in the request message. The server is indicating that it is unable or unwilling to complete the request using the same major version as the client, as described in section 3.1, other than with this error message.",
);

/** Message documentation
 * @author <adam.karminski@wikia-inc.com>
 */
$messages[ 'qqq' ] = array(
        'friendlyerrorpages' => "Friendly Error Pages",
        'friendlyerrorpages-desc' => "{{desc}}",
);