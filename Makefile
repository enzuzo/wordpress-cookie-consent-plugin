PREFIX=enzuzo-cookie-consent
VERSION=$(shell git describe --tags --abbrev=0)
ZIP=${PREFIX}-${VERSION}.zip

release:
	rm -f ${ZIP}
	git archive --format zip --prefix ${PREFIX}/ --output ~/${ZIP} main
