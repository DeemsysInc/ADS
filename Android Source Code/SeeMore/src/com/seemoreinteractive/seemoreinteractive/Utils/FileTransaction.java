package com.seemoreinteractive.seemoreinteractive.Utils;


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
import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.stream.StreamResult;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import android.os.Environment;
import android.util.Log;

import com.seemoreinteractive.seemoreinteractive.Model.ChangeLogModel;
import com.seemoreinteractive.seemoreinteractive.Model.ClientTriggers;
import com.seemoreinteractive.seemoreinteractive.Model.ClosetModel;
import com.seemoreinteractive.seemoreinteractive.Model.Offers;
import com.seemoreinteractive.seemoreinteractive.Model.OrderModel;
import com.seemoreinteractive.seemoreinteractive.Model.ProductModel;
import com.seemoreinteractive.seemoreinteractive.Model.ProfileModel;
import com.seemoreinteractive.seemoreinteractive.Model.Stores;
import com.seemoreinteractive.seemoreinteractive.Model.Triggers;
import com.seemoreinteractive.seemoreinteractive.Model.UserStoreTriggers;
import com.seemoreinteractive.seemoreinteractive.Model.Visual;
import com.seemoreinteractive.seemoreinteractive.Model.Visuals;
import com.seemoreinteractive.seemoreinteractive.Model.WishListModel;

public class FileTransaction {
	public void setVisuals(Visuals visuals) {

		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.Trigger_Location, "serializable");
	
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
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.Trigger_Location, "serializable");
		Visuals visuals = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						visuals = (Visuals) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetVisuals: " + e);
					e.printStackTrace();
				} catch (Exception e) {
					Log.i("TAG", "Exception in GetVisuals: " + e);
					e.printStackTrace();
				}
			} else {
				visuals = new Visuals();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetVisuals: " + e);
			e.printStackTrace();
		}
		return visuals;
	}

	public String downloadAndSaveImage(String imageURL, String imageName) {
		try {
			String extension = imageURL.substring(imageURL.lastIndexOf('.'), imageURL.length());
			//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", imageName + extension);
			File file = new File(Constants.Trigger_Location, imageName + extension);
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
			return file.toURI().toString();

		} catch (Exception e) {
			Log.i("TAG", "Exception while downloading and saving images: " + e);
		}
		return null;
	}

	public void getListOfFiles() {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/");
		File file = new File(Constants.Trigger_Location);
		for (int i = 0; i < file.list().length; i++) {
			Log.i("TAG", "File: " + file.list()[i]);
		}
		try {
			FileInputStream fstream = new FileInputStream(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.metaio.MobileSDKExample/files/" + "TrackingData_MarkerlessFast.xml");
			DataInputStream in = new DataInputStream(fstream);
			BufferedReader br = new BufferedReader(new InputStreamReader(in));
			String strLine;
			while ((strLine = br.readLine()) != null) {
				Log.i("TAG", strLine);
			}
			in.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public void createXML(Visuals visuals, String arrClientIds, String mDetectedCosName) {
 	try {
 		    ArrayList<String> arrgetClientGrpIds = new ArrayList<String>();
			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docFactory.newDocumentBuilder();
			Document doc = docBuilder.parse(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
		    Element sensor  =doc.getElementById("sensorID");
	        Triggers triggers = getClientTriggers();
        	List<ClientTriggers> clTrigger = null;
        	int index = 0;
			if(triggers.size() > 0)
			{
                clTrigger = triggers.getAllTrigger();               
			    index     = clTrigger.size();
			    arrgetClientGrpIds = triggers.getClientGrpIds(arrClientIds);
			}
			ArrayList<String> list = new ArrayList<String>();
			ArrayList<String> connList = new ArrayList<String>();
			for (Visual visual : visuals.list())
			{
				String imageUrl =visual.getImageFile();
				if(!list.contains(visual.getImageFile()))
				{
				Element sensorCOS = doc.createElement("SensorCOS");
				sensorCOS.setAttribute("id","" + visual.getTriggerId());
		 		sensor.appendChild(sensorCOS);
		 		Element sensorCosID = doc.createElement("SensorCosID");
		 		sensorCosID.appendChild(doc.createTextNode("Patch" + visual.getTriggerId()));
		 		sensorCOS.appendChild(sensorCosID);
		 		Element sensorParameters = doc.createElement("Parameters");
		 		sensorCOS.appendChild(sensorParameters);
		 		Element referenceImage = doc.createElement("ReferenceImage");
		 		referenceImage.setAttribute("id",""+visual.getTriggerId());
		 		String filePath = visual.getImageFile();
		 		if (filePath != null) 
		 		{
		 			referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
		 			sensorParameters.appendChild(referenceImage);	 					
					//visual.getMarker().setIndex(index);
		 		}else {
		 					index--;
		 				}
		 		list.add(imageUrl);
				}	
			}
				Element connections  =doc.getElementById("ConnectionsID");				
				
				for (Visual visual : visuals.list())
				{
					if(!connList.contains(visual.getImageFile()))
					{
					Element cos = doc.createElement("COS");
					cos.setAttribute("id","" + visual.getTriggerId());
					connections.appendChild(cos);
					Element cosName = doc.createElement("Name");
					cosName.appendChild(doc.createTextNode(""+visual.getTriggerId()));
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
					SensorCosID.appendChild(doc.createTextNode("Patch" + visual.getTriggerId()));
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
					connList.add(visual.getImageFile());
				}
			}
				
				
			    NodeList nodeList    = doc.getElementsByTagName("SensorCOS");	
		        NodeList cosNodeList = doc.getElementsByTagName("COS");	
		        if(mDetectedCosName.equals("90006")){
				 for(int n=0;n<nodeList.getLength();n++){
			        	Element element1 = (Element) nodeList.item(n);
			        	String id = element1.getAttribute("id");
				        	if(id.startsWith("900"))
				        		element1.getParentNode().removeChild(element1);
			        	}
					 for(int n=0;n<cosNodeList.getLength();n++){
				        	Element element = (Element) cosNodeList.item(n);
				        	String id = element.getAttribute("id");
				        	if(id.startsWith("900"))			        		
				        			element.getParentNode().removeChild(element);
				        	}
		        }else{
		        	     for(int n=0;n<nodeList.getLength();n++){
				        	Element element1 = (Element) nodeList.item(n);
				        	String id = element1.getAttribute("id");
					        	if(arrgetClientGrpIds.contains(id)){
					        		element1.getParentNode().removeChild(element1);					        		
					        	}
				        	}
				        	
					     
						 for(int n=0;n<cosNodeList.getLength();n++){
					        	Element element = (Element) cosNodeList.item(n);
					        	String id = element.getAttribute("id");
					        	if(arrgetClientGrpIds.contains(id)){		        		
						        	 element.getParentNode().removeChild(element);						        			
						         }
					     }
		        }
				        
			doc.normalize();		
			TransformerFactory transformerFactory = TransformerFactory.newInstance();
			Transformer transformer = transformerFactory.newTransformer();
			DOMSource source = new DOMSource(doc);
			//StreamResult result = new StreamResult(new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/", "TrackingData_Marker.xml"));
			 File theDir = new File(Constants.Trigger_Location);
             
             // if the directory does not exist, create it
             if (!theDir.exists()) {
               theDir.mkdir();  
             }
			StreamResult result = new StreamResult(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
			transformer.transform(source, result);
			// Log.i(Constants.TAG,"Doc Text: "+doc.);
		} catch (Exception e) {
			Log.i("TAG", "Exception in saving XML: " + e);
			e.printStackTrace();
		}
	}
	
	

	public void createClientXML() {
 	try {
			File theDir = new File(Constants.Trigger_Location);	        
	        // if the directory does not exist, create it
	        if (!theDir.exists()) {
	          theDir.mkdir();  
	        }
 		
			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docFactory.newDocumentBuilder();

			// root elements
			Document doc = docBuilder.newDocument();
			Element rootElement = doc.createElement("TrackingData");
			doc.appendChild(rootElement);
			Element sensors = doc.createElement("Sensors");
			rootElement.appendChild(sensors);
			Element sensor = doc.createElement("Sensor");
			sensor.setAttribute("id", "sensorID");
			sensor.setAttribute("type", "FeatureBasedSensorSource");
			sensor.setAttribute("subtype", "Fast");
			sensors.appendChild(sensor);
			// SensorId
			Element sensorID = doc.createElement("SensorID");
			sensorID.appendChild(doc.createTextNode("FeatureTracking1"));
			sensor.appendChild(sensorID);
			// Parameters
			Element parameters = doc.createElement("Parameters");
			Element FeatureDescriptorAlignment = doc.createElement("FeatureDescriptorAlignment");
			FeatureDescriptorAlignment.appendChild( doc.createTextNode("regular"));
			Element maxobjects = doc.createElement("MaxObjectsToTrackInParallel");
			maxobjects.appendChild(doc.createTextNode("2"));
			Element maxobjframe = doc.createElement("MaxObjectsToDetectPerFrame");
			maxobjframe.appendChild(doc.createTextNode("2"));
			Element SimilarityThreshold = doc.createElement("SimilarityThreshold");
			SimilarityThreshold.appendChild(doc.createTextNode("0.7"));    			
			parameters.appendChild(maxobjects);
			parameters.appendChild(maxobjframe);
			parameters.appendChild(SimilarityThreshold);
			sensor.appendChild(parameters);
			Triggers triggers = getClientTriggers();
        	List<ClientTriggers> clTrigger = null;
        	int index = 0;
			if(triggers.size() > 0)
			{
                clTrigger = triggers.getAllTrigger();
                 for (  ClientTriggers c : clTrigger) { 
                	index++;
	            	Element sensorCOS = doc.createElement("SensorCOS");
	            	sensorCOS.setAttribute("id","" + c.getId());
	 				sensor.appendChild(sensorCOS);
	 				Element sensorCosID = doc.createElement("SensorCosID");
	 				sensorCosID.appendChild(doc.createTextNode("Patch" +c.getId()));
	 				sensorCOS.appendChild(sensorCosID);
	 				Element sensorParameters = doc.createElement("Parameters");
	 				sensorCOS.appendChild(sensorParameters);
	 				Element referenceImage = doc.createElement("ReferenceImage");
	 				referenceImage.setAttribute("id","" + c.getId());
	 				String filePath = c.getClientImage();
	 				//Log.i("filePath",filePath);
	 				//Log.i("index",""+index);
	 				if (filePath != null) {
	 					referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
	 					sensorParameters.appendChild(referenceImage);
	 				}else {
	 					index--;
 					}
                }	    	
		  }
			Element connections = doc.createElement("Connections");
			connections.setAttribute("id", "ConnectionsID");
			rootElement.appendChild(connections);

			if(triggers.size() > 0)
			{
                clTrigger = triggers.getAllTrigger();
                for (  ClientTriggers c : clTrigger) {
             
				Element cos = doc.createElement("COS");
				cos.setAttribute("id","" + c.getId());
				connections.appendChild(cos);
				Element cosName = doc.createElement("Name");
				cosName.appendChild(doc.createTextNode(""+c.getId()));
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
				SensorCosID.appendChild(doc.createTextNode("Patch" +c.getId()));
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
			}
			TransformerFactory transformerFactory = TransformerFactory.newInstance();
			Transformer transformer = transformerFactory.newTransformer();
			DOMSource source = new DOMSource(doc);
			
			//StreamResult result = new StreamResult(new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/", "TrackingData_Marker.xml"));
			File file1 = new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml");
			StreamResult result = new StreamResult(file1);
			transformer.transform(source, result);
			
		} catch (Exception e) {
			Log.i("TAG", "Exception in saving XML: " + e);
			e.printStackTrace();
		}
	}
	public void removeXMLNode(String triggerVal) {		
		try {
			DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder documentBuilder= documentBuilderFactory.newDocumentBuilder();			
	        Document doc = documentBuilder.parse(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
	        NodeList nodeList = doc.getElementsByTagName("SensorCOS");	
	        NodeList cosNodeList = doc.getElementsByTagName("COS");	
	        if(triggerVal.equals("90006")){
			 for(int n=0;n<nodeList.getLength();n++){
		        	Element element1 = (Element) nodeList.item(n);
		        	String id = element1.getAttribute("id");
			        	if(id.startsWith("900"))
			        		element1.getParentNode().removeChild(element1);
		        	}
				 for(int n=0;n<cosNodeList.getLength();n++){
			        	Element element = (Element) cosNodeList.item(n);
			        	String id = element.getAttribute("id");
			        	if(id.startsWith("900"))			        		
			        			element.getParentNode().removeChild(element);
			        	}
	        }else{
	        	 for(int n=0;n<nodeList.getLength();n++){
			        	Element element1 = (Element) nodeList.item(n);
			        	String id = element1.getAttribute("id");
				        	if(id.equals(triggerVal)){
				        		element1.getParentNode().removeChild(element1);
				        		break;
				        	}
			        	}
			        	
				     
					 for(int n=0;n<cosNodeList.getLength();n++){
				        	Element element = (Element) cosNodeList.item(n);
				        	String id = element.getAttribute("id");
					        	if(id.equals(triggerVal)){		        		
					        			element.getParentNode().removeChild(element);
					        			break;
					        	}
				        	}
	        }
			        
				 doc.normalize();					
				 TransformerFactory transformerFactory = TransformerFactory.newInstance();
				 Transformer transformer = transformerFactory.newTransformer();					
				 DOMSource source = new DOMSource(doc);					
				 File file1 = new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml");
				 StreamResult result = new StreamResult(file1);
				 transformer.transform(source, result);
				
		} catch (SAXException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (ParserConfigurationException e) {
			e.printStackTrace();
		
		} catch (TransformerConfigurationException e) {
			e.printStackTrace();		
	    } catch (TransformerException e) {
	    	e.printStackTrace();
	    }
	}
	public void setClientTriggers(Triggers newTriggers) {
		try{
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File theDir = new File(Constants.Trigger_Location);
        
        // if the directory does not exist, create it
        if (!theDir.exists()) {
          theDir.mkdir();  
        }
		File file = new File(Constants.Trigger_Location, "triggers");
	
		if (!file.exists()){
			try {
				file.createNewFile();
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		try {
			final ObjectOutputStream savedos = new ObjectOutputStream(new FileOutputStream(file));
			try {
				savedos.writeObject(newTriggers);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
		}catch(Exception e){
			e.printStackTrace();
		}
	}

	public Triggers getClientTriggers() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.Trigger_Location, "triggers");
		Triggers triggers = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						triggers = (Triggers) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetVisuals: " + e);
					e.printStackTrace();
				}
			} else {
				triggers = new Triggers();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetVisuals: " + e);
			e.printStackTrace();
		}
		return triggers;
	}
	public void updateClientXML(Triggers newTriggers) {
		try {

			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docFactory.newDocumentBuilder();
			Document doc = docBuilder.parse(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
		    Element sensor  =doc.getElementById("sensorID");
		 	
		        Visuals visual = getVisuals();
	        	int index = 0;
				if(visual.size() > 0){
				  index = visual.size();
				}
				ArrayList<String> list = new ArrayList<String>();
				ArrayList<String> connList = new ArrayList<String>();
				for (ClientTriggers trigger : newTriggers.list())
				{
					String imageUrl =trigger.getClientImage();
					if(!list.contains(trigger.getClientImage()))
					{
					Element sensorCOS = doc.createElement("SensorCOS");
					sensorCOS.setAttribute("id","" + trigger.getId());
			 		sensor.appendChild(sensorCOS);
			 		Element sensorCosID = doc.createElement("SensorCosID");
			 		sensorCosID.appendChild(doc.createTextNode("Patch" +trigger.getId()));
			 		sensorCOS.appendChild(sensorCosID);
			 		Element sensorParameters = doc.createElement("Parameters");
			 		sensorCOS.appendChild(sensorParameters);
			 		Element referenceImage = doc.createElement("ReferenceImage");
			 		referenceImage.setAttribute("id",""+trigger.getId());
			 		String filePath = trigger.getClientImage();
			 		if (filePath != null) 
			 		{
			 			referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
			 			sensorParameters.appendChild(referenceImage);	
			 			index++;
						
			 		}else {
			 					index--;
			 				}
			 		list.add(imageUrl);
					}	
				}
					Element connections  =doc.getElementById("ConnectionsID");				
					
					for (ClientTriggers trigger : newTriggers.list())
					{
						if(!connList.contains(trigger.getClientImage()))
						{
						Element cos = doc.createElement("COS");
						cos.setAttribute("id","" + trigger.getId());
						connections.appendChild(cos);
						Element cosName = doc.createElement("Name");
						cosName.appendChild(doc.createTextNode(""+trigger.getId()));
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
						SensorCosID.appendChild(doc.createTextNode("Patch" + trigger.getId()));
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
						connList.add(trigger.getClientImage());
					}
				}
	 		
				TransformerFactory transformerFactory = TransformerFactory.newInstance();
				Transformer transformer = transformerFactory.newTransformer();
				DOMSource source = new DOMSource(doc);
				 File theDir = new File(Constants.Trigger_Location);
	             
	               // if the directory does not exist, create it
	               if (!theDir.exists()) {
	                 theDir.mkdir();  
	               }
				//StreamResult result = new StreamResult(new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/", "TrackingData_Marker.xml"));
				StreamResult result = new StreamResult(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
				transformer.transform(source, result);
				// Log.i(Constants.TAG,"Doc Text: "+doc.);
			} catch (Exception e) {
				Log.i("TAG", "Exception in saving XML: " + e);
				e.printStackTrace();
			}
	}
	
	public void setOffers(Offers offers) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userOffers");
	
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
				savedos.writeObject(offers);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public Offers getOffers() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userOffers");
		Offers offers = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						offers = (Offers) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetUserOffer: " + e);
					e.printStackTrace();
				}
			} else {
				offers = new Offers();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetUserOffer: " + e);
			e.printStackTrace();
		}
		return offers;
	}
	
	public void setStores(Stores stores) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "clientstores");
	
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
				savedos.writeObject(stores);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public Stores getStores() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "clientstores");
		Stores stores = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						stores = (Stores) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetClientStores: " + e);
					e.printStackTrace();
				}
			} else {
				stores = new Stores();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetClientStores: " + e);
			e.printStackTrace();
		}
		return stores;
	}
	
	public void setStoresTriggers(UserStoreTriggers userStoreTriggers) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userStoreTriggers");
	
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
				savedos.writeObject(userStoreTriggers);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public UserStoreTriggers getStoresTriggers() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userStoreTriggers");
		UserStoreTriggers userStoreTriggers = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						userStoreTriggers = (UserStoreTriggers) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetTriggers: " + e);
					e.printStackTrace();
				}catch (Exception e) {
					e.printStackTrace();
				}
			} else {
				userStoreTriggers = new UserStoreTriggers();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetTriggers: " + e);
			e.printStackTrace();
		}
		return userStoreTriggers;
	}
	
	public void setCloset(ClosetModel closet) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userCloset");
	
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
				savedos.writeObject(closet);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public ClosetModel getCloset() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userCloset");
		ClosetModel closet = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						closet = (ClosetModel) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetCloset: " + e);
					e.printStackTrace();
				}
			} else {
				closet = new ClosetModel();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetCloset: " + e);
			e.printStackTrace();
		}
		return closet;
	}
	
	public void setWishList(WishListModel wishlist) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userWishlist");	
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
				savedos.writeObject(wishlist);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	public WishListModel getWishList() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userWishlist");
		WishListModel wishlist = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						wishlist = (WishListModel) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetWishlist: " + e);
					e.printStackTrace();
				}
			} else {
				wishlist = new WishListModel();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetWishlist: " + e);
			e.printStackTrace();
		}
		return wishlist;
	}
	public void setProfile(ProfileModel profilemodel) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userprofile");	
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
				savedos.writeObject(profilemodel);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public ProfileModel getProfile() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userprofile");
		ProfileModel profilemodel = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						profilemodel = (ProfileModel) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetWishlist: " + e);
					e.printStackTrace();
				}
			} else {
				profilemodel = new ProfileModel();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetWishlist: " + e);
			e.printStackTrace();
		}
		return profilemodel;
	}
	public void setProduct(ProductModel productModel) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userproduct");	
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
				savedos.writeObject(productModel);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	public ProductModel getProduct() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "userproduct");
		ProductModel productModel = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						productModel = (ProductModel) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetProduct: " + e);
					e.printStackTrace();
				}
			} else {
				productModel = new ProductModel();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetProduct: " + e);
			e.printStackTrace();
		}
		return productModel;
	}	
	
	public void setMyOffers(Offers offers) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "myoffers");
	
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
				savedos.writeObject(offers);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	public Offers getMyOffers() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "myoffers");
		Offers offers = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						offers = (Offers) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetMyOffers: " + e);
					e.printStackTrace();
				}
			} else {
				offers = new Offers();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetMyOffers: " + e);
			e.printStackTrace();
		}
		return offers;
	}	
	
	
	public void setChangeLog(ChangeLogModel changeLogModel) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "changeLog");
	
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
				savedos.writeObject(changeLogModel);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	public ChangeLogModel getChangeLog() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "changeLog");
		ChangeLogModel changeLogModel = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						changeLogModel = (ChangeLogModel) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetMyOffers: " + e);
					e.printStackTrace();
				}
			} else {
				changeLogModel = new ChangeLogModel();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetMyOffers: " + e);
			e.printStackTrace();
		}
		return changeLogModel;
	}
	
	
	
	public void setOrder(OrderModel orderModel) {
		//File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "orderModel");
	
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
				savedos.writeObject(orderModel);
			} catch (Exception e) {
				e.printStackTrace();
			}
			savedos.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	public OrderModel getOrder() {
	//	File file = new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.seemoreinteractive/files/", "serializable");
		File file = new File(Constants.LOCATION, "orderModel");
		OrderModel orderModel = null;
		try {
			if (file.exists()) {
				try {
					final ObjectInputStream oins = new ObjectInputStream(new FileInputStream(file));
					try {
						orderModel = (OrderModel) oins.readObject();
					} catch (Exception e) {
						e.printStackTrace();
					}
					oins.close();
				} catch (EOFException e) {
					Log.i("TAG", "Exception in GetMyOffers: " + e);
					e.printStackTrace();
				}
			} else {
				orderModel = new OrderModel();
			}
		} catch (Exception e) {
			Log.i("TAG", "Exception in GetMyOffers: " + e);
			e.printStackTrace();
		}
		return orderModel;
	}
	
	
	public void createClientNewXML(Triggers newTriggers) {
	 	try {
				File theDir = new File(Constants.Trigger_Location);	        
		        // if the directory does not exist, create it
		        if (!theDir.exists()) {
		          theDir.mkdir();  
		        }
	 		
				DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
				DocumentBuilder docBuilder = docFactory.newDocumentBuilder();

				// root elements
				Document doc = docBuilder.newDocument();
				Element rootElement = doc.createElement("TrackingData");
				doc.appendChild(rootElement);
				Element sensors = doc.createElement("Sensors");
				rootElement.appendChild(sensors);
				Element sensor = doc.createElement("Sensor");
				sensor.setAttribute("id", "sensorID");
				sensor.setAttribute("type", "FeatureBasedSensorSource");
				sensor.setAttribute("subtype", "Fast");
				sensors.appendChild(sensor);
				// SensorId
				Element sensorID = doc.createElement("SensorID");
				sensorID.appendChild(doc.createTextNode("FeatureTracking1"));
				sensor.appendChild(sensorID);
				// Parameters
				Element parameters = doc.createElement("Parameters");
				Element FeatureDescriptorAlignment = doc.createElement("FeatureDescriptorAlignment");
				FeatureDescriptorAlignment.appendChild( doc.createTextNode("regular"));
				Element maxobjects = doc.createElement("MaxObjectsToTrackInParallel");
				maxobjects.appendChild(doc.createTextNode("2"));
				Element maxobjframe = doc.createElement("MaxObjectsToDetectPerFrame");
				maxobjframe.appendChild(doc.createTextNode("2"));
				Element SimilarityThreshold = doc.createElement("SimilarityThreshold");
				SimilarityThreshold.appendChild(doc.createTextNode("0.7"));    			
				parameters.appendChild(maxobjects);
				parameters.appendChild(maxobjframe);
				parameters.appendChild(SimilarityThreshold);
				sensor.appendChild(parameters);
				Triggers triggers = getClientTriggers();
	        	List<ClientTriggers> clTrigger = null;
	        	int index = 0;
	        	for (ClientTriggers c : newTriggers.list())
				{
	                //clTrigger = triggers.getAllTrigger();
	                // for (  ClientTriggers c : clTrigger) { 
	                	index++;
		            	Element sensorCOS = doc.createElement("SensorCOS");
		            	sensorCOS.setAttribute("id","" + c.getId());
		 				sensor.appendChild(sensorCOS);
		 				Element sensorCosID = doc.createElement("SensorCosID");
		 				sensorCosID.appendChild(doc.createTextNode("Patch" +c.getId()));
		 				sensorCOS.appendChild(sensorCosID);
		 				Element sensorParameters = doc.createElement("Parameters");
		 				sensorCOS.appendChild(sensorParameters);
		 				Element referenceImage = doc.createElement("ReferenceImage");
		 				referenceImage.setAttribute("id","" + c.getId());
		 				String filePath = c.getClientImage();
		 				//Log.i("filePath",filePath);
		 				//Log.i("index",""+index);
		 				if (filePath != null) {
		 					referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
		 					sensorParameters.appendChild(referenceImage);
		 				}else {
		 					index--;
	 					}
	                //}	    	
			  }
				Element connections = doc.createElement("Connections");
				connections.setAttribute("id", "ConnectionsID");
				rootElement.appendChild(connections);

				if(newTriggers.list().size() > 0)
				{
					for (ClientTriggers c : newTriggers.list())
					{
	             
					Element cos = doc.createElement("COS");
					cos.setAttribute("id","" + c.getId());
					connections.appendChild(cos);
					Element cosName = doc.createElement("Name");
					cosName.appendChild(doc.createTextNode(""+c.getId()));
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
					SensorCosID.appendChild(doc.createTextNode("Patch" +c.getId()));
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
				}
				TransformerFactory transformerFactory = TransformerFactory.newInstance();
				Transformer transformer = transformerFactory.newTransformer();
				DOMSource source = new DOMSource(doc);
				
				//StreamResult result = new StreamResult(new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/", "TrackingData_Marker.xml"));
				File file1 = new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml");
				StreamResult result = new StreamResult(file1);
				transformer.transform(source, result);
				
			} catch (Exception e) {
				Log.i("TAG", "Exception in saving XML: " + e);
				e.printStackTrace();
			}
		}
	
	
	
	public void createLatestXML(ArrayList<String> remArrayTriggerUrls, ArrayList<String> remArrayTriggerIds) {
	 	try {

			File theDir = new File(Constants.Trigger_Location);	        
	        // if the directory does not exist, create it
	        if (!theDir.exists()) {
	          theDir.mkdir();  
	        }
 		
			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docFactory.newDocumentBuilder();

			// root elements
			Document doc = docBuilder.newDocument();
			Element rootElement = doc.createElement("TrackingData");
			doc.appendChild(rootElement);
			Element sensors = doc.createElement("Sensors");
			rootElement.appendChild(sensors);
			Element sensor = doc.createElement("Sensor");
			sensor.setAttribute("id", "sensorID");
			sensor.setAttribute("type", "FeatureBasedSensorSource");
			sensor.setAttribute("subtype", "Fast");
			sensors.appendChild(sensor);
			// SensorId
			Element sensorID = doc.createElement("SensorID");
			sensorID.appendChild(doc.createTextNode("FeatureTracking1"));
			sensor.appendChild(sensorID);
			// Parameters
			Element parameters = doc.createElement("Parameters");
			Element FeatureDescriptorAlignment = doc.createElement("FeatureDescriptorAlignment");
			FeatureDescriptorAlignment.appendChild( doc.createTextNode("regular"));
			Element maxobjects = doc.createElement("MaxObjectsToTrackInParallel");
			maxobjects.appendChild(doc.createTextNode("2"));
			Element maxobjframe = doc.createElement("MaxObjectsToDetectPerFrame");
			maxobjframe.appendChild(doc.createTextNode("2"));
			Element SimilarityThreshold = doc.createElement("SimilarityThreshold");
			SimilarityThreshold.appendChild(doc.createTextNode("0.7"));    			
			parameters.appendChild(maxobjects);
			parameters.appendChild(maxobjframe);
			parameters.appendChild(SimilarityThreshold);
			sensor.appendChild(parameters);
			int index=0;
				ArrayList<String> list = new ArrayList<String>();
				ArrayList<String> connList = new ArrayList<String>();
				for (int i=0;i<remArrayTriggerUrls.size();i++)
				{
					index++;
					String imageUrl =remArrayTriggerUrls.get(i);
					if(!list.contains(imageUrl))
					{
					Element sensorCOS = doc.createElement("SensorCOS");
					sensorCOS.setAttribute("id","" + remArrayTriggerIds.get(i));
			 		sensor.appendChild(sensorCOS);
			 		Element sensorCosID = doc.createElement("SensorCosID");
			 		sensorCosID.appendChild(doc.createTextNode("Patch" + remArrayTriggerIds.get(i)));
			 		sensorCOS.appendChild(sensorCosID);
			 		Element sensorParameters = doc.createElement("Parameters");
			 		sensorCOS.appendChild(sensorParameters);
			 		Element referenceImage = doc.createElement("ReferenceImage");
			 		referenceImage.setAttribute("id",""+remArrayTriggerIds.get(i));
			 		String filePath = imageUrl;
			 		if (filePath != null) 
			 		{
			 			referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
			 			sensorParameters.appendChild(referenceImage);	 					
					//	visual.getMarker().setIndex(index);
			 		}else {
			 					index--;
			 				}
			 		list.add(imageUrl);
					}	
				}
					Element connections = doc.createElement("Connections");
					connections.setAttribute("id", "ConnectionsID");
					rootElement.appendChild(connections);			
					
					for (int i=0;i<remArrayTriggerUrls.size();i++)
					{
						//Log.e(" remArrayTriggerIds.get(i))", remArrayTriggerIds.get(i));
						
						Element cos = doc.createElement("COS");
						cos.setAttribute("id","" + remArrayTriggerIds.get(i));
						connections.appendChild(cos);
						Element cosName = doc.createElement("Name");
						cosName.appendChild(doc.createTextNode(""+remArrayTriggerIds.get(i)));
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
						SensorCosID.appendChild(doc.createTextNode("Patch" + remArrayTriggerIds.get(i)));
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
				//StreamResult result = new StreamResult(new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/", "TrackingData_Marker.xml"));
				
				StreamResult result = new StreamResult(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
				transformer.transform(source, result);
				// Log.i(Constants.TAG,"Doc Text: "+doc.);
			} catch (Exception e) {
				Log.i("TAG", "Exception in saving XML: " + e);
				e.printStackTrace();
			}
		}
	
	public void updateXML(ArrayList<String> remArrayTriggerUrls, ArrayList<String> remArrayTriggerIds) {
		try {

			DocumentBuilderFactory docFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = docFactory.newDocumentBuilder();
			Document doc = docBuilder.parse(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
		    Element sensor  =doc.getElementById("sensorID");
		 	
		      
				ArrayList<String> list = new ArrayList<String>();
				ArrayList<String> connList = new ArrayList<String>();
				int index=0;
				for (int i=0;i<remArrayTriggerUrls.size();i++)
				{
					index++;
					String imageUrl =remArrayTriggerUrls.get(i);
					if(!list.contains(imageUrl))
					{
					Element sensorCOS = doc.createElement("SensorCOS");
					sensorCOS.setAttribute("id","" + remArrayTriggerIds.get(i));
			 		sensor.appendChild(sensorCOS);
			 		Element sensorCosID = doc.createElement("SensorCosID");
			 		sensorCosID.appendChild(doc.createTextNode("Patch" + remArrayTriggerIds.get(i)));
			 		sensorCOS.appendChild(sensorCosID);
			 		Element sensorParameters = doc.createElement("Parameters");
			 		sensorCOS.appendChild(sensorParameters);
			 		Element referenceImage = doc.createElement("ReferenceImage");
			 		referenceImage.setAttribute("id",""+remArrayTriggerIds.get(i));
			 		String filePath = imageUrl;
			 		if (filePath != null) 
			 		{
			 			referenceImage.appendChild(doc.createTextNode(filePath.substring(filePath.lastIndexOf('/') + 1, filePath.length())));
			 			sensorParameters.appendChild(referenceImage);	 					
					//	visual.getMarker().setIndex(index);
			 		}else {
			 					index--;
			 				}
			 		list.add(imageUrl);
					}	
				}
					Element connections  =doc.getElementById("ConnectionsID");				
					
					for (int i=0;i<remArrayTriggerUrls.size();i++)
					{
						String imageUrl =remArrayTriggerUrls.get(i);
						if(!connList.contains(imageUrl))
						{
						Element cos = doc.createElement("COS");
						cos.setAttribute("id","" + remArrayTriggerIds.get(i));
						connections.appendChild(cos);
						Element cosName = doc.createElement("Name");
						cosName.appendChild(doc.createTextNode(""+remArrayTriggerIds.get(i)));
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
						SensorCosID.appendChild(doc.createTextNode("Patch" + remArrayTriggerIds.get(i)));
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
						connList.add(imageUrl);
					}
				}
	 		
				TransformerFactory transformerFactory = TransformerFactory.newInstance();
				Transformer transformer = transformerFactory.newTransformer();
				DOMSource source = new DOMSource(doc);
				 File theDir = new File(Constants.Trigger_Location);
	             
	               // if the directory does not exist, create it
	               if (!theDir.exists()) {
	                 theDir.mkdir();  
	               }
				//StreamResult result = new StreamResult(new File(Environment.getExternalStorageDirectory().getAbsolutePath() + "/Android/data/com.digitalimperia.MetaioDemos/files/", "TrackingData_Marker.xml"));
				StreamResult result = new StreamResult(new File(Constants.Trigger_Location, "TrackingData_ClientMarker.xml"));
				transformer.transform(source, result);
				// Log.i(Constants.TAG,"Doc Text: "+doc.);
			} catch (Exception e) {
				Log.i("TAG", "Exception in saving XML: " + e);
				e.printStackTrace();
			}
	}
}