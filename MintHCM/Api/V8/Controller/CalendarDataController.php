<?php

namespace Api\V8\Controller;

use Api\V8\Param\CalendarDataParams;
use Api\V8\Service\CalendarDataService;
use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

class CalendarDataController extends BaseController
{
    protected $calendarDataService;

    public function __construct(CalendarDataService $calendarDataService)
    {
        $this->calendarDataService = $calendarDataService;
    }
    
    public function getCalendarData(Request $request, Response $response, array $args, CalendarDataParams $params)
    {
        try {
            $jsonResponse = $this->calendarDataService->getCalendarData($params, $request);

            return $this->generateResponse($response, $jsonResponse, 200);
        } catch (Exception $exception) {
            return $this->generateErrorResponse($response, $exception, 400);
        }
    }
}
