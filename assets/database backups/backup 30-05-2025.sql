PGDMP          
            }           postgres    17.4    17.4 7    m           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            n           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            o           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            p           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            q           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4976            �            1255    18091    update_snip_likes()    FUNCTION       CREATE FUNCTION public.update_snip_likes() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    UPDATE snips
    SET challenge_points = (
        SELECT COUNT(*) FROM user_snip_likes WHERE snip_id = NEW.snip_id
    )
    WHERE id = NEW.snip_id;
    RETURN NULL;
END;
$$;
 *   DROP FUNCTION public.update_snip_likes();
       public               postgres    false            �            1259    18092 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    image text,
    type text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    18098    comments    TABLE     
  CREATE TABLE public.comments (
    id integer NOT NULL,
    creator character varying(16),
    post_name character varying(16),
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    child_of integer,
    likes integer DEFAULT 0
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    18105    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218            r           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    18106    drafts    TABLE     ,  CREATE TABLE public.drafts (
    id integer NOT NULL,
    creator text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    type character varying(32),
    description text,
    tags text[],
    file_location character varying(255),
    variation_of text,
    challenge_of text
);
    DROP TABLE public.drafts;
       public         heap r       postgres    false            �            1259    18112    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220            s           0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    18113    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variation_of text,
    challenge_of text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    18122    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222            t           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    18123    snips_with_likes    VIEW     �  CREATE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
 #   DROP VIEW public.snips_with_likes;
       public       v       postgres    false            �            1259    18127    user_snip_likes    TABLE     d   CREATE TABLE public.user_snip_likes (
    user_id integer NOT NULL,
    snip_id integer NOT NULL
);
 #   DROP TABLE public.user_snip_likes;
       public         heap r       postgres    false            �            1259    18130    users    TABLE     �  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text,
    followers text[],
    following text[],
    remember_token text,
    likedcomments text[]
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    18136    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    226            u           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    227            �            1259    18137    users_with_likes    VIEW     	  CREATE VIEW public.users_with_likes AS
 SELECT u.id,
    u.username,
    u.email,
    u.password,
    u.likedsnippets,
    u.savedsnippets,
    u.bio,
    u.followers,
    u.following,
    u.remember_token,
    COALESCE(sum(s.challenge_likes), (0)::numeric) AS user_challenge_likes
   FROM (public.users u
     LEFT JOIN public.snips_with_likes s ON (((u.username)::text = s.creator)))
  GROUP BY u.id, u.username, u.email, u.password, u.likedsnippets, u.savedsnippets, u.bio, u.followers, u.following, u.remember_token;
 #   DROP VIEW public.users_with_likes;
       public       v       postgres    false    226    226    224    224    226    226    226    226    226    226    226    226            �           2604    18142    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            �           2604    18143 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            �           2604    18144    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            �           2604    18145    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    227    226            a          0    18092 
   challenges 
   TABLE DATA           c   COPY public.challenges (name, description, date_start, date_end, winners, image, type) FROM stdin;
    public               postgres    false    217   �J       b          0    18098    comments 
   TABLE DATA           `   COPY public.comments (id, creator, post_name, content, created_at, child_of, likes) FROM stdin;
    public               postgres    false    218   �K       d          0    18106    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   ZL       f          0    18113    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of) FROM stdin;
    public               postgres    false    222   �L       h          0    18127    user_snip_likes 
   TABLE DATA           ;   COPY public.user_snip_likes (user_id, snip_id) FROM stdin;
    public               postgres    false    225   �R       i          0    18130    users 
   TABLE DATA           �   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following, remember_token, likedcomments) FROM stdin;
    public               postgres    false    226   6S       v           0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 18, true);
          public               postgres    false    219            w           0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 24, true);
          public               postgres    false    221            x           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 62, true);
          public               postgres    false    223            y           0    0    users_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.users_id_seq', 11, true);
          public               postgres    false    227            �           2606    18147    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            �           2606    18149    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            �           2606    18151    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            �           2606    18153    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            �           2606    18155    snips unique_file_location 
   CONSTRAINT     ^   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT unique_file_location UNIQUE (file_location);
 D   ALTER TABLE ONLY public.snips DROP CONSTRAINT unique_file_location;
       public                 postgres    false    222            �           2606    18157 $   user_snip_likes user_snip_likes_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_pkey PRIMARY KEY (user_id, snip_id);
 N   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_pkey;
       public                 postgres    false    225    225            �           2606    18159    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    226            �           2606    18161    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    226            �           2606    18163    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    226            _           2618    18126    snips_with_likes _RETURN    RULE     �  CREATE OR REPLACE VIEW public.snips_with_likes AS
 SELECT s.id,
    s.creator,
    s.views,
    s.created_at,
    s.description,
    s.element_type,
    s.tags,
    s.file_location,
    s.likes,
    s.saved,
    s.variation_of,
    s.challenge_of,
    count(usl.snip_id) AS challenge_likes
   FROM (public.snips s
     LEFT JOIN public.user_snip_likes usl ON ((s.id = usl.snip_id)))
  GROUP BY s.id;
 �  CREATE OR REPLACE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
       public               postgres    false    222    222    222    222    222    222    222    222    222    222    222    222    225    4794    224            �           2606    18165    comments comments_child_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_child_of_fkey FOREIGN KEY (child_of) REFERENCES public.comments(id);
 I   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_child_of_fkey;
       public               postgres    false    218    218    4790            �           2606    18170    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    4788    217    220            �           2606    18175    drafts drafts_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    4804    226    220            �           2606    18180    comments fk_post_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_post_name FOREIGN KEY (post_name) REFERENCES public.snips(file_location);
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_post_name;
       public               postgres    false    4796    218    222            �           2606    18185    comments fk_user_name    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT fk_user_name FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 ?   ALTER TABLE ONLY public.comments DROP CONSTRAINT fk_user_name;
       public               postgres    false    218    226    4804            �           2606    18190    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    4804    226    222            �           2606    18195 ,   user_snip_likes user_snip_likes_snip_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_snip_id_fkey FOREIGN KEY (snip_id) REFERENCES public.snips(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_snip_id_fkey;
       public               postgres    false    4794    225    222            �           2606    18200 ,   user_snip_likes user_snip_likes_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_user_id_fkey;
       public               postgres    false    225    4802    226            �           2606    18205    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    217    4788    222            a   3  x���Qk�0�g��M]����ǎ��(��G�M1�n~�Ym7aqsu (���~\�V�c��V�s� i��*d�	A+�=�C.I���4��Ү���M��L+BC �H*GM�C�t�ؘX�%�qX�n��}�!\���n��-���\Z�JɣÍA2I"K.�}W�Qv�>��WJ8=��`.��x����*��;��gx=����Z)��@%Г����J8���A�NB}�g�"/GTm�]�ZT����E����4&jKW��@��S<��?��&���Z��[��2���n��V����a�7E⻮�H�      b   ~   x�=�K�  ��p�ٹ*a�rO��DI`��[Ml�֏��Ú*)��T�ʊ�:~�K����KXv�B�I�I	���W��j  F���K�v�5���8yCҞ�KǅVF�|?��3�>�*�      d   c   x��K@0 ���� a�����"#>m	#~qw^� �	`�D ��q^PRn4��"���6�w��|h���</t�9v֝��f���_աR��       f   �  x��W�n�8}f��yW�"%ꆢ@�v�]l�`�@���i��D*eG	��;��K{S۰�yf�����J*��(����1���K=��������K�[#�V�6F+�g|֣Yg����׹�q�$/K�|����_��X�MQ=����1R����� ���� )M��cQ��B�>U��XaQ�J(��^�ǧ�Y,��e�
 9�^��4N��- ��M꒏N�~�F�,��bY��ޏ�#D�-<�1�� IY�x�H�B����x.ڬ���Za�dō��Ɏ�9�8�J/��	5bc��2_��y5�gf��`��/��h���A��)cn��~�>(m
��v%MV�LW�ݼ��o-��p��*�h�	���ε���_@��[ÛֹԺ\��E���r����NQ�wQK
�Ҁ���~L�"�����S�U�j��*�lD���&SH� %�����}t#�D��
���� ��ʮ<??�������[��ng�۹�Y����܏ܞ��{�t��;�q@?IR/q��y���B݉'T�.�s�����s�ι�����mv_�5��9��PҔ�=�������5:��� �ÔwPWE��ż^�=J_����9(ui��y��v��֖r�v8t�鲳٢;5wn��7�Uwf��åi��k1�b�K|k�R�1�>W�ږu�)6N}ύ� �(|�<��n�R�%,�41��Re����F5��z�6�y��:�-��j4��SJ\�%I���
x�c4H�rq`�}̪�T�qpz�}h�мC/�Q Q�K�q�q%���>_CEg_@�[%죐d�e�^Q��M=�&ԋI�V��jx��uS�>�񲟉�S�!A\��X�E��0�b���"�m˫���DNI�O^s�\Rq�0#����=���R,@����^�8f$e�K�(�o
$T�3詼��hp)j�-/+���l�Ձ�Y#��C�tw��-mW��4*�ѕ�K��|�4�sF��MRA�ʳ�����݃hzB� �A
U���"�5����l9�6�����,i�����d����0~��Q�L_����{�ʗ0��Y��/ �;?Lѵ�|�������mU㚭&O{8�6o�9�@'�hW�{�Os�ć>
��%��l�x!sn8���#���3|��FFt���EWN�_HXY�C;��&�29�� 5�0J�g��ܜd�$l�Q�R+[��E,�N͙E^�}��y��iU
�\q�ܹ��%��`n� ]�X+�X�FAb���	�Y�B��׆�T8�
(��������I̹������4pY�i��]-�N���P'��
����[�6;Mj����yy
��h0�E�VB7����tj�h�^'`��uN����0�ԏ�0(`��@s����Wf.G6��L���	�<�$�gk4�ô̱�=�}`�s܁�4�XC�e]��x��1�E/:�lN���KK���I4k��j�W��N��e�ݳ��� ��1�      h   ^   x�-���0�x�
� �.���Q?':і�����W������#;���Ha���4�����F
%����ܘ¶
&�Lj0�Z�M�u����<2      i   /  x����n�HF��)��f$�;1�j0�8���F�zS@����b�V�}w�'#��2�Bq��UO�pr\H�脊��"�+� �tX`��9��c��͇���� �{��d�0�	VUP,��G�xo�5-�G'6�)(<�RêF$y���٣��:�P����7��D
D��2����ճ�Y:�L�:j�&|#�2����R�&�|�f�Qkڠ.o�sϿ�����Q�/n�{��.��!N؛�L�Dev��-�0��9��e�${c?�@�^��K7-§�H�M�@��a�}��u��	��bx���G���p�"m�C^����*L1�������r��#�@�%�?W�	��-e����O�xr<itj�5�wc���d:)Ӫrؔ!��{�|?>�����߯��M@�����W���l��/pY{����`3u��u<56����HJ���Q�F��1 �ᕙ�e���ڍW9t9��=��Y���ɢ��f;�sB��[��ͅ���/)(1�P�	���7X1<.�yao�qEƞ���j3I��z>6��q��ߒ��|���'���C�m S*N��}��۸�u+�$u���d%����\y�gSy|I�޳�cŪ�˼Xk> ��C��]M�����
�V�dŞ"�i��E��s+Pr_�f��j�I����Hm:���K�M���x���rY]�Žu���`A���/¹#:���\��1��m��3����9
�!"=����{:6\E����&�U2�0h��0��I�셖{6�D��Z�^+J�����=����Z     