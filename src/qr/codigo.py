import qrcode

data = "https://lugengar.github.io/lou_comidas/"

qr = qrcode.QRCode(
    version=1,  # Controla el tamaño del QR, de 1 a 40 (si es mayor, más datos se pueden almacenar)
    error_correction=qrcode.constants.ERROR_CORRECT_L,  # Control de error (L, M, Q, H)
    box_size=10,  # Tamaño de cada caja que forma el código QR
    border=4,  # Tamaño del borde en cajas
)

qr.add_data(data)
qr.make(fit=True)

img = qr.make_image(fill='black', back_color='white')

img.save("qr.png")
