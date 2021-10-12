# Question
  AndroidStudio导出项目后，再次导入无法使用问题
# Answer
  在gradle.properties文件中添加android.overridePathCheck=true
# Question
  提示handle的handleMessage方法被弃用
# Answer
```java
    @SuppressLint("HandlerLeak")
    Handler handler = new Handler(Looper.getMainLooper()) {
        private int i = 3;
        @SuppressLint("SetTextI18n")
        @Override
        public void handleMessage(@NonNull Message msg) {
            if (msg.what == 1) {
                textview.setText(Integer.toString(i--));
            }
            super.handleMessage(msg);
        }
    };
```
  
