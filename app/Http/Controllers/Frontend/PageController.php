<?php

namespace App\Http\Controllers\Frontend;

use App\Models\DocumentsModel;
use App\Models\UserDocumentsCountriesModel;
use App\Models\CountriesModel;
use App\Models\BillsModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Log;

class PageController extends Controller {
    
    public function index() {
        // Retrieve all countries
        $countries = CountriesModel::all();
        $scripts = ['documents.js'];
        
        return view('frontend.pages.index', [
            'countries' => $countries,
            'scripts' => $scripts
        ]);
    }

    public function aboutUs() {
        $hasDarkNav = true;
        return view('frontend.pages.about-us', compact('hasDarkNav'));
    }

    public function search() {
        $hasBlueNav = true;
        $countries = CountriesModel::all();  // Fetch all countries for both logged and non-logged users
        $documents = collect(); 

        if ($countries->isNotEmpty()) {
            $firstCountryId = $countries[0]->country_id;
            $documents = DocumentsModel::where('document_country_id', $firstCountryId)->get();

            if (Session::has('loggedUser')) {
                $loggedUser = Session::get('loggedUser');
                $planId = $loggedUser['plan_id'] ?? null;

                if ($planId == 1) {
                    // Plan 1: Always allow document download (no changes needed)
                } elseif (in_array($planId, [3, 4])) {
                    $bill = BillsModel::where('user_id', $loggedUser['user_id'])
                        ->where('plan_id', $planId)
                        ->first();

                    if (!$bill || $bill->payment_status != 1) {
                        // If bill is not paid, remove document files
                        $documents = $documents->map(function($document) {
                            unset($document->document_file);
                            return $document;
                        });
                    }
                } elseif ($loggedUser['user_role_id'] == 2) {
                    // Role 2: Check specific countries and bills for access
                    $documents = $documents->map(function($document) use ($loggedUser, $firstCountryId) {
                        $userDocumentCountry = UserDocumentsCountriesModel::where('user_id', $loggedUser['user_id'])
                            ->where('country_id', $firstCountryId)
                            ->first();

                        if ($userDocumentCountry) {
                            $bill = BillsModel::where('bill_id', $userDocumentCountry->bill_id)->first();
                            if (!$bill || $bill->payment_status != 1) {
                                unset($document->document_file);
                            }
                        } else {
                            unset($document->document_file);
                        }
                        return $document;
                    });
                }
            } else {
                // For non-logged users, remove document file access
                $documents = $documents->map(function($document) {
                    unset($document->document_file);
                    return $document;
                });
            }
        }

        $scripts = ['documents.js'];
        return view('frontend.pages.search', [ 
            'countries' => $countries, 
            'documents' => $documents, 
            'scripts' => $scripts
        ]);
    }

    public function searchCountry($countryId) {
        $hasBlueNav = true;
        $countries = CountriesModel::all();
        $documents = DocumentsModel::where('document_country_id', $countryId)->get();

        if (Session::has('loggedUser')) {
            $loggedUser = Session::get('loggedUser');
            $planId = $loggedUser['plan_id'] ?? null;

            if ($planId == 1) {
                // Plan 1: Always allow document download
            } elseif (in_array($planId, [3, 4])) {
                $bill = BillsModel::where('user_id', $loggedUser['user_id'])
                    ->where('plan_id', $planId)
                    ->first();

                if (!$bill || $bill->payment_status != 1) {
                    $documents = $documents->map(function($document) {
                        unset($document->document_file);
                        return $document;
                    });
                }
            } elseif ($loggedUser['user_role_id'] == 2) {
                $documents = $documents->map(function($document) use ($loggedUser, $countryId) {
                    $userDocumentCountry = UserDocumentsCountriesModel::where('user_id', $loggedUser['user_id'])
                        ->where('country_id', $countryId)
                        ->first();

                    if ($userDocumentCountry) {
                        $bill = BillsModel::where('bill_id', $userDocumentCountry->bill_id)->first();
                        if (!$bill || $bill->payment_status != 1) {
                            unset($document->document_file);
                        }
                    } else {
                        unset($document->document_file);
                    }
                    return $document;
                });
            }
        } else {
            $documents = $documents->map(function($document) {
                unset($document->document_file);
                return $document;
            });
        }

        if (request()->ajax()) {
            $documentsHtml = view('frontend.partials.documents', compact('documents'))->render();
            return response()->json(['success' => true, 'documentsHtml' => $documentsHtml]);
        }

        $scripts = ['documents.js'];
        return view('frontend.pages.search', [
            'countries' => $countries,
            'documents' => $documents,
            'scripts' => $scripts
        ]);
    }

    public function searchCountryHome(Request $request) {
        $hasLightNav = true;
        Log::info('Received request in searchCountryHome:', ['request' => $request->all()]);
        $searchInput = $request->input('search');
        $validatedData = $request->validate(['search' => 'required|string|max:255']);
        $country = CountriesModel::where('country', $searchInput)->first();

        if (!$country) {
            return response()->json(['success' => false, 'message' => 'No country found matching your search.']);
        }

        $countryId = $country->country_id;
        $documents = DocumentsModel::where('document_country_id', $countryId)->get();

        if (Session::has('loggedUser')) {
            $loggedUser = Session::get('loggedUser');
            $planId = $loggedUser['plan_id'] ?? null;

            if ($planId == 1) {
                // Plan 1: Always allow document download
            } elseif (in_array($planId, [3, 4])) {
                $bill = BillsModel::where('user_id', $loggedUser['user_id'])
                    ->where('plan_id', $planId)
                    ->first();

                if (!$bill || $bill->payment_status != 1) {
                    $documents = $documents->map(function($document) {
                        unset($document->document_file);
                        return $document;
                    });
                }
            } elseif ($loggedUser['user_role_id'] == 2) {
                $documents = $documents->map(function($document) use ($loggedUser, $countryId) {
                    $userDocumentCountry = UserDocumentsCountriesModel::where('user_id', $loggedUser['user_id'])
                        ->where('country_id', $countryId)
                        ->first();

                    if ($userDocumentCountry) {
                        $bill = BillsModel::where('bill_id', $userDocumentCountry->bill_id)->first();
                        if (!$bill || $bill->payment_status != 1) {
                            unset($document->document_file);
                        }
                    } else {
                        unset($document->document_file);
                    }
                    return $document;
                });
            }
        } else {
            $documents = $documents->map(function($document) {
                unset($document->document_file);
                return $document;
            });
        }

        return response()->json([
            'success' => true,
            'countryName' => $country->country,
            'countryId' => $countryId,
            'documents' => $documents,
        ]);
    }

    public function network() {
        $hasDarkNav = true;
        return view('frontend.pages.network', compact('hasDarkNav'));
    }

    public function contactUs() {
        $hasDarkNav = true;
        return view('frontend.pages.contact-us', compact('hasDarkNav'));
    }
}
