package com.seemoreinteractive.virtualshot.utils;

import java.io.BufferedReader;
import java.io.DataInputStream;
import java.io.EOFException;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.net.URL;
import java.net.URLConnection;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.stream.StreamResult;

import org.w3c.dom.Document;
import org.w3c.dom.Element;

import android.util.Log;

import com.seemoreinteractive.virtualshot.model.Visual;
import com.seemoreinteractive.virtualshot.model.Visuals;

public class FileTransaction {

	// private final static String location =
	// "/data/data/com.pillartechnology.Trends/files/";

	public void setVisuals(Visuals visuals) {

		File file = new File(Constants.LOCATION, "serializable");
		if (!file.exists()) {
			try {
				file.createNewFile();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		try {
			final ObjectOutputStream savedos = new ObjectOutputStream(new FileOutputStream(file));
			try {
				savedos.writeObject(visuals);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public Visuals getVisuals() {
		File file = new File(Constants.LOCATION, "serializable");
		//Log.i("file in getVisuals", "file: " + file);
		Visuals visuals = null;
		try {
			//Log.i("getVisuals file exists", "file.exists(): " + file.exists());
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					//Log.i("ObjectInputStream", "oins: " + oins);
					try {
						visuals = (Visuals) oins.readObject();
						//Log.i("visuals", "visuals: " + visuals);
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i(Constants.TAG, "Exception in GetVisuals: " + e);
					e.printStackTrace();
				}
			} else {
				//Log.i("else", "else: " + file.exists());
				visuals = new Visuals();
				//Log.i("visuals else", "visuals else: " + visuals);
			}
		} catch (Exception e) {
			Log.i(Constants.TAG, "Exception in GetVisuals: " + e);
			e.printStackTrace();
		}
		return visuals;
	}

	public String downloadAndSaveImage(String imageURL, String imageName) {
		try {
			Log.e("imageURL", ""+imageURL+" "+imageName);
			if(!imageURL.equals("null")){
				String extension = imageURL.substring(imageURL.lastIndexOf('.'), imageURL.length());
				Log.e("extension", ""+extension);
				File file = new File(Constants.LOCATION, imageName + extension);
				if (file.exists()) {
					file.delete();
				}
				file.createNewFile();
				URLConnection conn = new URL(imageURL).openConnection();
				conn.connect();
				InputStream is = conn.getInputStream();
				OutputStream out = new FileOutputStream(file);
				byte buf[] = new byte[2048];
				int len;
				while ((len = is.read(buf)) > 0)
					out.write(buf, 0, len);
				out.close();
				is.close();
				// Log.i(Constants.TAG, "Absolute path: " + file.getAbsoluteFile());
				//Log.i(Constants.TAG, "URI: " + file.toURI().toString());
				return file.toURI().toString();				
			}

		} catch (Exception e) {
			Log.i(Constants.TAG, "Exception while downloading and saving images: " + e);
			e.printStackTrace();
		}
		return null;
	}

	public void getListOfFiles() {
		File file = new File(Constants.LOCATION);
		for (int i = 0; i < file.list().length; i++) {
			Log.i(Constants.TAG, "File: " + file.list()[i]);
		}
		try {
			FileInputStream fstream = new FileInputStream(Constants.LOCATION + "TrackingData_MarkerlessFast.xml");
			DataInputStream in = new DataInputStream(fstream);
			BufferedReader br = new BufferedReader(new InputStreamReader(in));
			String strLine;
			while ((strLine = br.readLine()) != null) {
				Log.i(Constants.TAG, strLine);
			}
			in.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public static int demoMarkerId = 0;
	public void createXML(Visuals visuals) {
		try {

			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docFactory.newDocumentBuilder();

			// root elements
			Document doc = docBuilder.newDocument();
			Element rootElement = doc.createElement("TrackingData");
			doc.appendChild(rootElement);
			Element sensors = doc.createElement("Sensors");
			rootElement.appendChild(sensors);
			Element sensor = doc.createElement("Sensor");
			sensor.setAttribute("type", "FeatureBasedSensorSource");
			sensor.setAttribute("subtype", "fast");
			sensors.appendChild(sensor);
			// SensorId
			Element sensorID = doc.createElement("SensorID");
			sensorID.appendChild(doc.createTextNode("FeatureTracking1"));
			sensor.appendChild(sensorID);
			// Parameters
			Element parameters = doc.createElement("Parameters");
			sensor.appendChild(parameters);
			// FeatureBasedParameters
			Element featureBasedParameters = doc.createElement("FeatureBasedParameters");
			parameters.appendChild(featureBasedParameters);
			int index = 0;
			for (Visual visual : visuals.list()) {
				index++;
				Element sensorCOS = doc.createElement("SensorCOS");
				sensor.appendChild(sensorCOS);
				Element sensorCosID = doc.createElement("SensorCosID");
				sensorCosID.appendChild(doc.createTextNode("Patch" + index));
				sensorCOS.appendChild(sensorCosID);
				Element sensorParameters = doc.createElement("Parameters");
				sensorCOS.appendChild(sensorParameters);
				Element referenceImage = doc.createElement("ReferenceImage");
				String filePath = visual.getMarker().getImageFile();
				if (filePath != null) {
					referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
					sensorParameters.appendChild(referenceImage);
					visual.getMarker().setIndex(index);
					if(visual.getMarker().getTitle().equals("DemoMarker")){
						demoMarkerId = visual.getMarker().getIndex();
					}
				} else {
					index--;
				}
			}
			Element connections = doc.createElement("Connections");
			rootElement.appendChild(connections);

			for (int j = 1; j <= index; j++) {
				/*Element cos = doc.createElement("COS");
				connections.appendChild(cos);
				Element cosName = doc.createElement("Name");
				cosName.appendChild(doc.createTextNode("MarkerlessCOS"+j));
				cos.appendChild(cosName);
				Element fuser = doc.createElement("Fuser");
				fuser.setAttribute("type", "SmoothingFuser");
				fuser.appendChild(doc
						.createTextNode("<Parameters><AlphaRotation>0.5</AlphaRotation><AlphaTranslation>0.8</AlphaTranslation><KeepPoseForNumberOfFrames>0</KeepPoseForNumberOfFrames></Parameters>"));
				cos.appendChild(fuser);
				Element sensorSource = doc.createElement("SensorSource");
				sensorSource.setAttribute("trigger", "1");
				sensorSource
						.appendChild(doc
								.createTextNode("<SensorID>FeatureTracking1</SensorID><SensorCosID>Patch1</SensorCosID><HandEyeCalibration><TranslationOffset><x>0</x><y>0</y><z>0</z></TranslationOffset><RotationOffset><x>0</x><y>0</y><z>0</z><w>1</w></RotationOffset></HandEyeCalibration><COSOffset><TranslationOffset><x>0</x><y>0</y><z>0</z></TranslationOffset><RotationOffset><x>0</x><y>0</y><z>0</z><w>1</w></RotationOffset></COSOffset>"));
				cos.appendChild(sensorSource);*/
				
				
				
				Element cos = doc.createElement("COS");
				connections.appendChild(cos);
				Element cosName = doc.createElement("Name");
				cosName.appendChild(doc.createTextNode("MarkerlessCOS"+j));
				cos.appendChild(cosName);
				Element fuser = doc.createElement("Fuser");
				fuser.setAttribute("type", "SmoothingFuser");
				Element ParametersM = doc.createElement("Parameters");
				Element AlphaRotation = doc.createElement("AlphaRotation");
				AlphaRotation.appendChild(doc.createTextNode("0.5"));
				Element AlphaTranslation = doc.createElement("AlphaTranslation");
				AlphaTranslation.appendChild(doc.createTextNode("0.8"));
				Element KeepPoseForNumberOfFrames = doc.createElement("KeepPoseForNumberOfFrames");
				KeepPoseForNumberOfFrames.appendChild(doc.createTextNode("0"));
				ParametersM.appendChild(AlphaRotation);
				ParametersM.appendChild(AlphaTranslation);
				ParametersM.appendChild(KeepPoseForNumberOfFrames);
				fuser.appendChild(ParametersM);
				//fuser.appendChild(doc.createTextNode("<Parameters><AlphaRotation>0.5</AlphaRotation><AlphaTranslation>0.8</AlphaTranslation><KeepPoseForNumberOfFrames>2</KeepPoseForNumberOfFrames></Parameters>"));
				cos.appendChild(fuser);
				Element sensorSource = doc.createElement("SensorSource");
				
				Element SensorID = doc.createElement("SensorID");
				SensorID.appendChild(doc.createTextNode("FeatureTracking1"));
				Element SensorCosID = doc.createElement("SensorCosID");
				SensorCosID.appendChild(doc.createTextNode("Patch" + j));
				Element HandEyeCalibration = doc.createElement("HandEyeCalibration");
				Element TranslationOffset = doc.createElement("TranslationOffset");
				Element x = doc.createElement("x");
				x.appendChild(doc.createTextNode("0"));
				Element y = doc.createElement("y");
				y.appendChild(doc.createTextNode("0"));
				Element z = doc.createElement("z");
				z.appendChild(doc.createTextNode("0"));
				TranslationOffset.appendChild(x);
				TranslationOffset.appendChild(y);
				TranslationOffset.appendChild(z);
				Element RotationOffset = doc.createElement("RotationOffset");
				Element x1 = doc.createElement("x");
				x1.appendChild(doc.createTextNode("0"));
				Element y1 = doc.createElement("y");
				y1.appendChild(doc.createTextNode("0"));
				Element z1 = doc.createElement("z");
				z1.appendChild(doc.createTextNode("0"));
				Element w = doc.createElement("w");
				w.appendChild(doc.createTextNode("1"));
				RotationOffset.appendChild(x1);
				RotationOffset.appendChild(y1);
				RotationOffset.appendChild(z1);
				RotationOffset.appendChild(w);
				HandEyeCalibration.appendChild(TranslationOffset);
				HandEyeCalibration.appendChild(RotationOffset);
				Element COSOffset = doc.createElement("COSOffset");
				Element TranslationOffset1 = doc.createElement("TranslationOffset");
				Element x2 = doc.createElement("x");
				x2.appendChild(doc.createTextNode("0"));
				Element y2 = doc.createElement("y");
				y2.appendChild(doc.createTextNode("0"));
				Element z2 = doc.createElement("z");
				z2.appendChild(doc.createTextNode("0"));
				TranslationOffset1.appendChild(x2);
				TranslationOffset1.appendChild(y2);
				TranslationOffset1.appendChild(z2);
				Element RotationOffset1 = doc.createElement("RotationOffset");
				Element x3 = doc.createElement("x");
				x3.appendChild(doc.createTextNode("0"));
				Element y3 = doc.createElement("y");
				y3.appendChild(doc.createTextNode("0"));
				Element z3 = doc.createElement("z");
				z3.appendChild(doc.createTextNode("0"));
				Element w3 = doc.createElement("w");
				w3.appendChild(doc.createTextNode("1"));
				RotationOffset1.appendChild(x3);
				RotationOffset1.appendChild(y3);
				RotationOffset1.appendChild(z3);
				RotationOffset1.appendChild(w3);
				COSOffset.appendChild(TranslationOffset1);
				COSOffset.appendChild(RotationOffset1);
				sensorSource.appendChild(SensorID);
				sensorSource.appendChild(SensorCosID);   
				sensorSource.appendChild(HandEyeCalibration);
				sensorSource.appendChild(COSOffset);    				
				cos.appendChild(sensorSource);
				
			}

			TransformerFactory transformerFactory = TransformerFactory.newInstance();
			Transformer transformer = transformerFactory.newTransformer();
			DOMSource source = new DOMSource(doc);
			StreamResult result = new StreamResult(new File(Constants.LOCATION, "TrackingData_MarkerlessFast.xml"));
			transformer.transform(source, result);
			// Log.i(Constants.TAG,"Doc Text: "+doc.);
		} catch (Exception e) {
			Log.i(Constants.TAG, "Exception in saving XML: " + e);
			e.printStackTrace();
		}
	}
}
