package com.seemoreinteractive.virtualshot;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.util.Date;
import java.util.List;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.pm.PackageManager;
import android.content.pm.ResolveInfo;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Matrix;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.seemoreinteractive.virtualshot.manager.SessionManager;
import com.seemoreinteractive.virtualshot.model.Image;
import com.seemoreinteractive.virtualshot.utils.Constants;

public class PreviewActivity extends Activity {
	ImageView image;
	String absolutePath = "";
	private SharedPreferences mPrefs;
	private Resources res = null;
	int deviceHeight = 0;
	int deviceWidth = 0;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		try{
			Log.i(Constants.TAG, "Preview: onCreate called.");
			if (isHTC()) {
				setContentView(R.layout.preview_htc);
			} else {
				setContentView(R.layout.preview);
			}
			DisplayMetrics displayMetrics = new DisplayMetrics();
			getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
			deviceHeight = displayMetrics.heightPixels;
			deviceWidth = displayMetrics.widthPixels;
			res = this.getResources();
			image = (ImageView) findViewById(R.id.preview_view);
			View save = findViewById(R.id.preview_save);
			View share = findViewById(R.id.preview_share);
			View fb = findViewById(R.id.preview_fb);
			View mms = findViewById(R.id.preview_mms);
			View cancel = findViewById(R.id.preview_cancel);
			

			String filepath = Constants.LOCATION+"camera_picture.png";			
			Log.e("Image.getCharacterBitmap()",""+Image.getCharacterBitmap());
			File imgFile = new  File(filepath);
			if(imgFile.exists()){
			Bitmap cameraBitmap = BitmapFactory.decodeFile(imgFile.getAbsolutePath());
			//cameraBitmap = Bitmap.createScaledBitmap(cameraBitmap, 1047, deviceHeight, false);
			//Bitmap bmOverlay = Bitmap.createScaledBitmap(cameraBitmap, 1047, deviceHeight,cameraBitmap.getConfig());
			Image.setCameraBitmap(cameraBitmap);
			}else{
				Image.setCameraBitmap(Image.getCharacterBitmap());
			}
			 
			// fb.setVisibility(View.GONE);
			// share.setVisibility(View.GONE);
			cancel.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						finish();
					} catch (Exception e) {
						// TODO: handle exception
						Toast.makeText(getApplicationContext(), "Error: PreviewActivity cancel.", Toast.LENGTH_LONG).show();
					}
				}
			});
			save.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						Date date = new Date();
						MediaStore.Images.Media.insertImage(getContentResolver(), Image.getCombinedBitmap(), date.toString(), "description");
						Toast.makeText(PreviewActivity.this, "Photo has been saved successfully", Toast.LENGTH_SHORT).show();
					} catch (Exception e) {
						Toast.makeText(PreviewActivity.this, "unable to save photo.", Toast.LENGTH_SHORT).show();
						e.printStackTrace();
					}
				}
			});
	
			if (mms != null) {
				mms.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View v) {
						try {
							File file = createTempFile();
							if (file != null) {
								Intent intent = new Intent("android.intent.action.SEND_MSG");
								intent.putExtra("sms_body", "");
								intent.setType("image/jpeg");
								intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(file));
								startActivity(Intent.createChooser(intent, "MMS"));
							}
						} catch (Exception e) {
							Toast.makeText(getApplicationContext(), "Error: PreviewActivity clicked on mms.", Toast.LENGTH_LONG).show();
							e.printStackTrace();
						}
					}
				});
			}
	
			share.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						File file = createTempFile();
						if (file != null) {
							Intent intent = new Intent(Intent.ACTION_SEND);
							intent.putExtra("sms_body", "");
							intent.setType("image/jpeg");
							intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(file));
							startActivity(Intent.createChooser(intent, "Share"));
						}
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: PreviewActivity clicked on share.", Toast.LENGTH_LONG).show();
						e.printStackTrace();
					}
				}
			});
	
			fb.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						LayoutInflater li = LayoutInflater.from(PreviewActivity.this);
						View promptsView = li.inflate(R.layout.fb_dialog, null);
						AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(PreviewActivity.this);
						alertDialogBuilder.setView(promptsView);
						// final EditText fbDescription = (EditText) promptsView
						// .findViewById(R.id.fb_description);
						alertDialogBuilder.setCancelable(true).setPositiveButton("OK", new DialogInterface.OnClickListener() {
							@Override
							public void onClick(DialogInterface dialog, int id) {
								try{
									mPrefs = getSharedPreferences(Constants.FB_PREF, Context.MODE_PRIVATE);
									Editor editor = mPrefs.edit();
									editor.putString("fb_photo_description", "New VirtualShot Image.");
									editor.commit();
									Intent intent = new Intent(PreviewActivity.this, FacebookActivity.class);
									startActivity(intent);
								} catch (Exception e) {
									Toast.makeText(getApplicationContext(), "Error: PreviewActivity clicked on fb alertDialogBuilder.", Toast.LENGTH_LONG).show();
								}
							}
						}).setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
							@Override
							public void onClick(DialogInterface dialog, int id) {
								dialog.cancel();
							}
						});
						AlertDialog alertDialog = alertDialogBuilder.create();
						alertDialog.setTitle("Confirmation");
						alertDialog.show();
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: PreviewActivity clicked on fb.", Toast.LENGTH_LONG).show();
					}
				}
			});
	
			// Button flickr = (Button) findViewById(R.id.preview_flickr);
			// flickr.setOnClickListener(new View.OnClickListener() {
			// @Override
			// public void onClick(View v) {
			// LayoutInflater li = LayoutInflater.from(PreviewActivity.this);
			// View dialogView = li.inflate(R.layout.flickr_dialog, null);
			// AlertDialog.Builder alertDialogBuilder = new
			// AlertDialog.Builder(PreviewActivity.this);
			// alertDialogBuilder.setView(dialogView);
			// final EditText flickrTitle = (EditText)
			// dialogView.findViewById(R.id.flickr_title);
			// final EditText flickrDescription = (EditText)
			// dialogView.findViewById(R.id.flickr_description);
			// alertDialogBuilder
			// .setCancelable(true)
			// .setPositiveButton("OK",
			// new DialogInterface.OnClickListener() {
			// public void onClick(DialogInterface dialog, int id) {
			// mPrefs =
			// getSharedPreferences(Constants.FLICKR_PREF,Context.MODE_PRIVATE);
			// Editor editor = mPrefs.edit();
			// editor.putString("flickr_title",flickrTitle.getText().toString());
			// editor.putString("flickr_description",flickrDescription.getText().toString());
			// editor.commit();
			// Intent intent = new
			// Intent(PreviewActivity.this,FlickrActivity.class);
			// startActivity(intent);
			// }
			// })
			// .setNegativeButton("Cancel",
			// new DialogInterface.OnClickListener() {
			// public void onClick(DialogInterface dialog, int id) {
			// dialog.cancel();
			// }
			// });
			// AlertDialog alertDialog = alertDialogBuilder.create();
			// alertDialog.setTitle("Image Details");
			// alertDialog.show();
			// }
			// });
	
			View twitter = findViewById(R.id.preview_twitter);
			twitter.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try{
						LayoutInflater li = LayoutInflater.from(PreviewActivity.this);
						View promptsView = li.inflate(R.layout.twitter_dialog, null);
						AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(PreviewActivity.this);
						alertDialogBuilder.setView(promptsView);
						final EditText tweet = (EditText) promptsView.findViewById(R.id.twitter_tweet);
						tweet.setText("@IntlTrends");
						alertDialogBuilder.setCancelable(true).setPositiveButton("OK", new DialogInterface.OnClickListener() {
							@Override
							public void onClick(DialogInterface dialog, int id) {
								try{
									mPrefs = getSharedPreferences(Constants.TWITTER_PREF, Context.MODE_PRIVATE);
									Editor editor = mPrefs.edit();
									editor.putString("tweet", tweet.getText().toString());
									editor.commit();
									Intent intent = new Intent(PreviewActivity.this, TwitterActivity.class);
									startActivity(intent);
								} catch (Exception e) {
									Toast.makeText(getApplicationContext(), "Error: PreviewActivity clicked on twitter alertDialogBuilder.", Toast.LENGTH_LONG).show();
								}
							}
						}).setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
							@Override
							public void onClick(DialogInterface dialog, int id) {
								dialog.cancel();
							}
						});
						AlertDialog alertDialog = alertDialogBuilder.create();
						alertDialog.setTitle("Enter Tweet");
						alertDialog.show();
					} catch (Exception e) {
						Toast.makeText(getApplicationContext(), "Error: PreviewActivity clicked on twitter.", Toast.LENGTH_LONG).show();
					}
				}
			});
	
			View email = findViewById(R.id.preview_email);
			email.setOnClickListener(new View.OnClickListener() {
				@Override
				public void onClick(View v) {
					try {
						File file = createTempFile();
						if (file != null) {
							Intent intent = new Intent(Intent.ACTION_SEND);
							intent.setType("message/rfc822");
							intent.putExtra(Intent.EXTRA_SUBJECT, "Check out my awesome pic!");
							intent.putExtra(Intent.EXTRA_TEXT, "Attached by Trends International");
							intent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(file));
							startActivity(Intent.createChooser(intent, "Email"));
						}
					} catch (Exception e) {
						Toast.makeText(PreviewActivity.this, "Error: PreviewActivity Unable to open installed email apps", Toast.LENGTH_SHORT).show();
					}
				}
			});
			Image.setCombinedBitmap(overlay(Image.getCameraBitmap(), Image.getCharacterBitmap()));
			image.setImageBitmap(Image.getCombinedBitmap());
			
		} catch (Exception e) {
			// TODO: handle exception
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity onCreate.", Toast.LENGTH_LONG).show();
		}
	}

	public File createTempFile() {
		try{
			File externalStorage = Environment.getExternalStorageDirectory();
			String sdcardPath = externalStorage.getAbsolutePath();
			File file = new File(sdcardPath + "/" + Constants.TEMP_IMAGE_NAME);
			try {
				if (file.isFile()) {
					file.delete();
				}
				if (file.createNewFile()) {
					ByteArrayOutputStream bytes = new ByteArrayOutputStream();
					Image.getCombinedBitmap().compress(Bitmap.CompressFormat.JPEG, 100, bytes);
					FileOutputStream fo = new FileOutputStream(file);
					fo.write(bytes.toByteArray());
					bytes.close();
					fo.close();
					return file;
				}
			} catch (Exception e) {
				Log.i(Constants.TAG, "Unable to create Image file");
				Toast.makeText(getApplicationContext(), "Error: PreviewActivity createTempFile - file storage error.", Toast.LENGTH_LONG).show();
			}
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity createTempFile.", Toast.LENGTH_LONG).show();
		}
		return null;
	}

	@Override
	public void onResume() {
		try{
			Log.i(Constants.TAG, "Preview Activity onResume called.");
			super.onResume();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity onResume.", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public void onBackPressed() {
		try{
			Intent data = new Intent();
			Bundle bundle = new Bundle();
			bundle.putInt("delay", 1000);
			data.putExtras(bundle);
			setResult(RESULT_OK, data);
			super.onBackPressed();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity onBackPressed.", Toast.LENGTH_LONG).show();
		}
	}

	@Override
	public void onStop() {
		try{
			super.onStop();
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity onStop.", Toast.LENGTH_LONG).show();
		}
	}

	public Bitmap overlay(Bitmap bmp1, Bitmap bmp2) {
		try{
			 Log.e(Constants.TAG,"Camera Image Width: "+bmp1.getWidth()+" Height: "+bmp1.getHeight());
			 Log.e(Constants.TAG,"Character Image Width: "+bmp2.getWidth()+" Height: "+bmp2.getHeight());

			 Log.e(Constants.TAG,"device Width: "+deviceWidth+" Height: "+deviceHeight);
			 Log.e(Constants.TAG,"MainActivity.matrix: "+MainActivity.matrix);
			//MainActivity.matrix.postTranslate((bmp1.getWidth() - deviceWidth) / 2, (bmp1.getHeight() - deviceHeight) / 2);
			 if(MainActivity.matrix != null){
				 MainActivity.matrix.postTranslate(0,0);
			 }
			 
			Bitmap bmOverlay = Bitmap.createBitmap(deviceWidth, bmp1.getHeight()-200, bmp1.getConfig());
			// Bitmap bmOverlay = Bitmap.createBitmap(1047, bmp1.getHeight(), bmp1.getConfig());
			Bitmap watermark = BitmapFactory.decodeResource(res, R.drawable.watermark);
			Bitmap legalImage = Image.getLegalBitmap();
			float markerTop = ((float) bmp1.getHeight()-200) - watermark.getHeight() - 25;
			Canvas canvas = new Canvas(bmOverlay);
			canvas.drawBitmap(bmp1, new Matrix(), null);
			canvas.drawBitmap(bmp2, MainActivity.matrix, null);
			canvas.drawBitmap(watermark, 25f, markerTop, null);
			if(legalImage!=null && SessionManager.getInstance().getLegalPosition()!=null){
				float legalLeftPx = 0f, legalTopPx = 0f;
				switch(SessionManager.getInstance().getLegalPosition()){
				case BOTTOM_LEFT:
					legalTopPx = bmp1.getHeight() - legalImage.getHeight();
					break;
					
				case BOTTOM_CENTER:
					legalLeftPx = (bmp1.getWidth() / 2) - (legalImage.getWidth() / 2);
					legalTopPx = bmp1.getHeight() - legalImage.getHeight();
					break;
					
				case BOTTOM_RIGHT:
					legalLeftPx = bmp1.getWidth() - legalImage.getWidth();
					legalTopPx = bmp1.getHeight() - legalImage.getHeight();
					break;
					
				case TOP_RIGHT:
					legalLeftPx = bmp1.getWidth() - legalImage.getWidth();
					break;
					
				case TOP_CENTER:
					legalLeftPx = (bmp1.getWidth() / 2) - (legalImage.getWidth() / 2);
					break;
				}				
				canvas.drawBitmap(legalImage, legalLeftPx, legalTopPx, null);
			}
			 
			return bmOverlay;
		} catch (Exception e) {
			// TODO: handle exception
			e.printStackTrace();
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity overlay.", Toast.LENGTH_LONG).show();
			return null;
		}
	}

	public Boolean isHTC() {
		try{
			boolean isHtc = false;
			Intent in = new Intent(Intent.ACTION_MAIN);
			in.addCategory(Intent.CATEGORY_HOME);
			List<ResolveInfo> resolves = PreviewActivity.this.getPackageManager().queryIntentActivities(in, PackageManager.MATCH_DEFAULT_ONLY);
			for (ResolveInfo info : resolves) {
				if (info.activityInfo != null && "com.htc.launcher.Launcher".equals(info.activityInfo.name)) {
					isHtc = true;
					break;
				}
			}
			return isHtc;
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getApplicationContext(), "Error: PreviewActivity isHTC.", Toast.LENGTH_LONG).show();
			return null;
		}
	}
}
