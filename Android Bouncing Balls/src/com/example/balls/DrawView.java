package com.example.balls;

import java.util.ArrayList;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.RectF;
import android.util.AttributeSet;
import android.util.Log;
import android.view.GestureDetector;
import android.view.MotionEvent;
import android.view.View;
import android.view.GestureDetector.OnGestureListener;




public class DrawView extends View implements OnGestureListener {

	private static ArrayList<Ball> balls = new ArrayList<Ball>();
	private static float initialVelocityConstant;
	private static float dragConstant;
	
	private GestureDetector gd;
	
	private static final String TAG = "DrawView"; 
	
	private Paint bgColor;
	
	public DrawView(Context context) {
		super(context);
		// TODO Auto-generated constructor stub
	}
	
	
	public DrawView(Context c, AttributeSet attrs) {
		super(c, attrs);
		
		gd = new GestureDetector(c, this);
		
		bgColor = new Paint();
		bgColor.setColor(0xff888888);
		
		initialVelocityConstant = 1f;
		dragConstant = 0.99f;
	}
	
	
	
	public boolean onTouchEvent(MotionEvent event) {
		return gd.onTouchEvent(event);
	}
	
	
	public static void resetBalls()
	{
		balls.clear();
	}
	
	
	public static float getDragConstant()
	{
		return dragConstant;
	}
	
	public static float getInitVel()
	{
		return initialVelocityConstant;
	}
	
	public static void setInitVel(float vel)
	{
		initialVelocityConstant = vel;
		
		if(initialVelocityConstant < 0)
			initialVelocityConstant = 0;
	}
	
	
	@Override
	public void onDraw(Canvas canvas) {
		
		canvas.drawPaint(bgColor);
		
		for(int i = 0; i<balls.size(); i++)
		{
			balls.get(i).update(balls);
			balls.get(i).draw(canvas);
		}
		
		invalidate();
	}
	
	

	@Override
	public boolean onDown(MotionEvent arg0) {
		// TODO Auto-generated method stub
		return true;	//this must be set to true
	}

	@Override
	public boolean onFling(MotionEvent arg0, MotionEvent arg1, float arg2,
			float arg3) {
		// TODO Auto-generated method stub
		return false;
	}
	

	@Override
	public void onLongPress(MotionEvent e) {
		// TODO Auto-generated method stub
		
		balls.add(new Ball(e.getX(), e.getY()));
	}
	

	@Override
	public boolean onScroll(MotionEvent start, MotionEvent end, float distanceX,
			float distanceY) {
		// TODO Auto-generated method stub
		
		for(int i = 0; i < balls.size(); i++)
		{
			if(balls.get(i).insideRadius(start.getX(), start.getY()))
			{
				balls.get(i).setVelocity(-initialVelocityConstant*distanceX, -initialVelocityConstant*distanceY);
			}
		}
		return true;
	}

	@Override
	public void onShowPress(MotionEvent arg0) {
		// TODO Auto-generated method stub
		
	}
	
	

	@Override
	public boolean onSingleTapUp(MotionEvent arg0) {
		// TODO Auto-generated method stub
		return false;
	}

}
