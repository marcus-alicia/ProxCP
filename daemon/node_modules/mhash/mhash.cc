#include <nan.h>

#ifdef __APPLE__
 #include <stdbool.h>
#endif

#include <mhash.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>

using namespace v8;

void reverse_bytes(unsigned char * start, unsigned long len)
{
	unsigned char *lo = start;
	unsigned char *hi = start + len - 1;
	unsigned char swap;
	while(lo<hi)
	{
		swap = *lo;
		*lo++ = *hi;
		*hi-- = swap;
	}
}

char * convert_to_hex(unsigned char * hash_data, unsigned long len)
{
	char *			converted;
	unsigned long	i;

	converted = (char *)malloc((len*2)+1);
	memset(converted, 0, (len*2)+1);

	for(i=0;i<len;i++)
	{
		sprintf(converted+(i*2), "%02x", hash_data[i]);
	}

	return converted;
}

char * hash(hashid hashType, unsigned char * data, unsigned long len)
{
	MHASH				td;
	unsigned char * 	hash_data;
	char *				converted=0;
	unsigned long		block_size;

	if((td=mhash_init(hashType))==MHASH_FAILED)
		return 0;

	mhash(td, data, len);

	if((hash_data=(unsigned char *)mhash_end(td)))
	{
		block_size = mhash_get_block_size(hashType);
		if(hashType==MHASH_ADLER32)	// Fixes #6
			reverse_bytes(hash_data, block_size);
		converted = convert_to_hex(hash_data, block_size);
		mhash_free(hash_data);
	}

	return converted;
}

hashid get_hash_type_by_name(const char * name)
{
	if(strcasecmp(name, "crc32")==0)
		return MHASH_CRC32;
	else if(strcasecmp(name, "crc32b")==0)
		return MHASH_CRC32B;
	else if(strcasecmp(name, "md2")==0)
		return MHASH_MD2;
	else if(strcasecmp(name, "md4")==0)
		return MHASH_MD4;
	else if(strcasecmp(name, "md5")==0)
		return MHASH_MD5;
	else if(strcasecmp(name, "haval128")==0)
		return MHASH_HAVAL128;
	else if(strcasecmp(name, "haval160")==0)
		return MHASH_HAVAL160;
	else if(strcasecmp(name, "haval192")==0)
		return MHASH_HAVAL192;
	else if(strcasecmp(name, "haval224")==0)
		return MHASH_HAVAL224;
	else if(strcasecmp(name, "haval256")==0)
		return MHASH_HAVAL256;
	else if(strcasecmp(name, "sha1")==0)
		return MHASH_SHA1;
	else if(strcasecmp(name, "sha224")==0)
		return MHASH_SHA224;
	else if(strcasecmp(name, "sha256")==0)
		return MHASH_SHA256;
	else if(strcasecmp(name, "sha384")==0)
		return MHASH_SHA384;
	else if(strcasecmp(name, "sha512")==0)
		return MHASH_SHA512;
	else if(strcasecmp(name, "ripemd128")==0)
		return MHASH_RIPEMD128;
	else if(strcasecmp(name, "ripemd160")==0)
		return MHASH_RIPEMD160;
	else if(strcasecmp(name, "ripemd256")==0)
		return MHASH_RIPEMD256;
	else if(strcasecmp(name, "ripemd320")==0)
		return MHASH_RIPEMD320;
	else if(strcasecmp(name, "tiger128")==0)
		return MHASH_TIGER128;
	else if(strcasecmp(name, "tiger160")==0)
		return MHASH_TIGER160;
	else if(strcasecmp(name, "tiger192")==0)
		return MHASH_TIGER192;
	else if(strcasecmp(name, "gost")==0)
		return MHASH_GOST;
	else if(strcasecmp(name, "whirlpool")==0)
		return MHASH_WHIRLPOOL;
	else if(strcasecmp(name, "adler32")==0)
		return MHASH_ADLER32;
	else if(strcasecmp(name, "snefru128")==0)
		return MHASH_SNEFRU128;
	else if(strcasecmp(name, "snefru256")==0)
		return MHASH_SNEFRU256;

	return (hashid)-1;
}

NAN_METHOD(Hash)
{
	Local<String> 	ret;
	char *			hashed=0;
	hashid			type;

	std::string name = *Nan::Utf8String(info[0]);
	type = get_hash_type_by_name(name.c_str());
	if(type==(hashid)-1)
	{
		info.GetReturnValue().Set(Nan::Null());
		return;
	}

	if(info[1]->IsString())
	{
		std::string data = *Nan::Utf8String(info[1]);
		hashed = hash(type, (unsigned char *)data.c_str(), data.length());
	}
	else if(node::Buffer::HasInstance(info[1]))
	{
		MaybeLocal<Object> data = info[1]->ToObject(Isolate::GetCurrent()->GetCurrentContext());
		hashed = hash(type, (unsigned char *)node::Buffer::Data(data.ToLocalChecked()), node::Buffer::Length(data.ToLocalChecked()));
	}

	if(!hashed)
	{
		info.GetReturnValue().Set(Nan::Null());
		return;
	}

	ret = Nan::New(hashed).ToLocalChecked();
	free(hashed);

	info.GetReturnValue().Set(ret);
}

NAN_MODULE_INIT(Init)
{
	Nan::Set(target, Nan::New<String>("hash").ToLocalChecked(), Nan::GetFunction(Nan::New<FunctionTemplate>(Hash)).ToLocalChecked());
}

NODE_MODULE(mhash, Init)
