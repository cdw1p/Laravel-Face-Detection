<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Softon\LaravelFaceDetect\Facades\FaceDetect;
use Image;
use File;

class MainController extends Controller
{
    public function index(Request $request) {
        return response()->json([
            'welcome' => 'Example Rest Server'
        ], 200);
    }

    public function upload(Request $request) {
        try {
            $request->validate([ 'file'  => 'required' ]);

            $fileRequest    = $request->file('file');
            $fileExtension  = $fileRequest->getClientOriginalExtension();
            $fileFullname   = strtoupper($fileExtension . '-ORI' . date('YmdHis') . $fileRequest->getSize()). '.' .$fileExtension;
            $filePathDest   = public_path('uploads') . '/' . $fileFullname;
            $filePathDest2  = public_path('uploads') . '/train//' . $fileFullname;
            $fileSaveLocal  = Image::make($fileRequest)->save($filePathDest);
            $fileSaveTrain  = FaceDetect::extract('D:/LEARNING/PHP/laravel-face-recog/public/uploads//' . $fileFullname)->save('D:/LEARNING/PHP/laravel-face-recog/public/uploads/train//' . $fileFullname);

            if ($fileSaveTrain) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan.'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error! Pattern wajah tidak dikenali.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error! Pattern wajah tidak ditemukan.'
            ], 500);
        }
    }

    public function check(Request $request) {
        try {
            $request->validate([ 'file'  => 'required' ]);

            $fileRequest    = $request->file('file');
            $fileExtension  = $fileRequest->getClientOriginalExtension();
            $fileFullname   = strtoupper($fileExtension . '-ORI' . date('YmdHis') . $fileRequest->getSize()). '.' .$fileExtension;
            $filePathDest   = public_path('uploads') . '/' . $fileFullname;
            $fileSaveLocal  = Image::make($fileRequest)->save($filePathDest);
            $fileCheckTrain = FaceDetect::extract('D:/LEARNING/PHP/laravel-face-recog/public/uploads//' . $fileFullname)->face_found;

            if ($fileCheckTrain) {
                return response()->json([
                    'success' => true,
                    'message' => 'Halo, wajah anda dikenali :)'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Error! Wajah tidak ditemukan.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error! Pattern wajah tidak ditemukan.'
            ], 500);
        }
    }
}