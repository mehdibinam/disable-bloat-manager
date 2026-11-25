# عکس ردمی / README screenshot — DO NOT REMOVE

عکسی که صاحب پروژه در README قرار داده، منبع آن `github.com/user-attachments` است و **در فایل ZIP افزونه وجود ندارد**. هنگام جایگزینی README با نسخه داخل ZIP، حتماً این تگ را دوباره درج کن.

The screenshot embedded in `README.md` is hosted on `github.com/user-attachments` and is NOT included in the plugin ZIP. Whenever README.md is replaced with the version from a new ZIP, this `<img>` tag MUST be restored (right after the Persian intro paragraph, before `### ایدهٔ اولیه`).

```html
<img width="1597" height="872" alt="disable-bloat-manager" src="https://github.com/user-attachments/assets/0cf19e4f-24b1-47f1-89c4-1312e2552f0d" />
```

## محل قرارگیری / Placement

بعد از پاراگراف معرفی فارسی و قبل از «ایدهٔ اولیه»:

```
## فارسی

**مدیریت کارایی وردپرس** ... 

<img ... />

### ایدهٔ اولیه
```

> نکته: اگر صاحب پروژه عکس دیگریگذاشت، شناسه asset جدید را در این فایل هم آپدیت کن.
